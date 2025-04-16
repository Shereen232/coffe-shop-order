<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use App\Models\TableModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends BaseController
{
    protected $productModel, $cartModel, $cartItemModel, $orderModel, $orderItemModel, $tableModel, $paymentModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->tableModel = new TableModel();
        $this->paymentModel = new PaymentModel();
    }

    public function checkout()
    {
        $sessionID = session('session')['table_id'] ?? null;
        if (!$sessionID) {
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
                'user_id'    => $cartModel->session_id,
                'total_price'  => $cartModel->total,
                'status'    => 'pending',
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
            }

            // Hapus semua item di keranjang
            $this->cartItemModel->where('cart_id', $cartModel->id)->delete();

            // Hapus data cart-nya juga
            $this->cartModel->delete($cartModel->id);

            $this->paymentModel->save([
                'order_id' => $orderId,
                'payment_method' => $this->request->getPost('metode_pembayaran'),
                'payment_status' => 'pending',
                'transaction_id' => 'TRX-' . strtoupper(uniqid()),
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
    
}
