<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use App\Models\SettingModel;
use App\Models\TableModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends BaseController
{
    protected $productModel, $cartModel, $cartItemModel, $orderModel, $orderItemModel, $tableModel, $paymentModel, $settingModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->tableModel = new TableModel();
        $this->paymentModel = new PaymentModel();
        $this->settingModel = new SettingModel();
    }

    public function checkout()
    {
        $paymentSetting = $this->settingModel->getPaymentSetting();
        $sessionID = session('session')['table_id'] ?? null;

        if (!$sessionID) {
            $currentURL = current_url();
            session()->set('redirect_url', $currentURL);
            return redirect()->to('auth/login')->with('error', 'Silakan login terlebih dahulu!');
        }
        $cart = $this->cartModel
            ->where('session_id', $sessionID)
            ->orderBy('id', 'DESC')
            ->first();
        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        $data = [
            'title' => 'Checkout',
            'orders' => $this->cartItemModel->select('cart_items.*, products.image, products.name')->where('cart_id', $cart->id)->join('products', 'cart_items.product_id = products.id')->findAll(),
            'total' => $cart->total,
            'paymentSetting' => $paymentSetting,
        ];

        return view('customer/checkout', $data);
    }

    public function prosesCheckout()
    {

        $db = \Config\Database::connect();

        try {
            // Mulai transaksi
            $db->transBegin();

            $cartModel = $this->cartModel->asObject()->where('session_id', session('session')['table_id'])->first();

            if (!$cartModel) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Keranjang tidak ditemukan!');
            }

            $cartItems = $this->cartItemModel->where('cart_id', $cartModel->id)->findAll();
            // Tambahkan item ke dalam cart
            $this->orderModel->save([
                'user_id'      => $cartModel->session_id,
                'nama'         => $this->request->getPost('nama'), 
                'total_price'  => $cartModel->total,
                'status'       => 'pending',
            ]);


            $orderId = $this->orderModel->getInsertID();

            foreach ($cartItems as $cartItem) {
                $this->orderItemModel->save([
                    'order_id'    => $orderId,
                    'product_id'  => $cartItem->product_id,
                    'quantity'    => $cartItem->qty,
                    'price'       => $cartItem->subtotal,
                    'subtotal'    => $cartItem->subtotal * $cartItem->qty
                ]);

                 $this->productModel->where('id', $cartItem->product_id)
                ->set('stock', 'stock - ' . (int) $cartItem->qty, false)
                ->update();
            }

            // Hapus semua item di keranjang
            $this->cartItemModel->where('cart_id', $cartModel->id)->delete();

            // Hapus data cart-nya juga
            $this->cartModel->delete($cartModel->id);

            $metode = $this->request->getPost('metode_pembayaran');

            $snapToken = null;

            if ($metode === 'online_payment') {
                $snapToken = $this->getSnapToken($orderId, $cartModel->total, $cartModel->session_id);
            }

            $this->paymentModel->save([
                'order_id' => $orderId,
                'payment_method' => $this->request->getPost('metode_pembayaran'),
                'payment_status' => 'pending',
                'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                'snaptoken'      => $snapToken,
            ]);

            // Commit transaksi
            if ($db->transStatus() === false) {
                // Rollback kalau ada yang gagal
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal memproses pesanan!');
            }

            $db->transCommit();

            return redirect()->to('order/' . $orderId)->with('success', 'Pesanan berhasil dibuat! Silakan cek email Anda untuk detail pesanan.');

        } catch (\Exception $e) {
            // Rollback kalau ada exception
            $db->transRollback();
            return json_encode(['status' => 'ERROR', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function order($id)
    {
        $order = $this->orderModel->asObject()->where('id', $id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan!');
        }

        $order_items = $this->orderItemModel->select('order_items.*, products.image, products.name')->where('order_id', $id)->join('products', 'order_items.product_id = products.id')->findAll();
        $table = $this->tableModel->asObject()->where('id', $order->user_id)->first();
        $payment = $this->paymentModel->where('order_id', $id)->first();
        $order->table = $table->table_number;
        $order->payment = $payment;

        return view('customer/order', [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'order_items' => $order_items,
        ]);
    }

    public function strukPDF($id)
    {
        $order = $this->orderModel->find($id);
        $orderItems = $this->orderItemModel->select('order_items.*, products.image, products.name')->where('order_id', $id)->join('products', 'order_items.product_id = products.id')->findAll();
        $table = $this->tableModel->asObject()->where('id', $order->user_id)->first();
        $payment = $this->paymentModel->where('order_id', $id)->first();
        $order->table = $table->table_number;
        $order->payment = $payment;

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Order tidak ditemukan");
        }

        $data = [
            'order' => $order,
            'order_items' => $orderItems
        ];

        $html = view('customer/order/struk', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('struk-order-' . $id . '.pdf', ['Attachment' => true]);
    }

    private function getSnapToken($orderID, $orderAmount, $orderTable)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderID,
                'gross_amount' => $orderAmount
            ],
            'customer_details' => [
                'user_id' => $orderTable,
                'email' => 'customer@example.com',
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);         

        return $snapToken;
    }
    
    public function submitProductReview($orderId, $productId)
    {
        $comment = $this->request->getPost('comment');

        if (!$comment) {
            return redirect()->back()->with('error', 'Komentar tidak boleh kosong!');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('product_reviews');

        // Cek apakah sudah ada review untuk order + produk ini
        $existing = $builder->where([
            'order_id' => $orderId,
            'product_id' => $productId
        ])->get()->getRow();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $builder->insert([
            'order_id' => $orderId,
            'product_id' => $productId,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim.');
    }

}
