<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\ProductModel;

class PaymentController extends BaseController
{
    protected $productModel, $cartModel, $cartItemModel, $orderModel, $orderItemModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function checkout()
    {
        $cart = $this->cartModel
            ->where('session_id', 151515)
            ->orderBy('id', 'DESC')
            ->first();
        if (!$cart) {
            return redirect()->to('/cart')->with('error', 'Keranjang kosong!');
        }

        $data = [
            'title' => 'Checkout',
            'orders' => $this->cartItemModel->select('cart_items.*, products.image, products.name')->where('cart_id', $cart->id)->join('products', 'cart_items.product_id = products.id')->findAll(),
            'total' => $cart->total,
        ];

        return view('customer/checkout', $data);
    }

    public function order($id)
    {

        $db = \Config\Database::connect();

        try {
            // Mulai transaksi
            $db->transBegin();

            $cartModel = $this->cartModel->asObject()->where('session_id', $id)->first();

            if (!$cartModel) {
                $db->transRollback();
                return json_encode(['status' => 'ERROR', 'message' => 'Cart tidak ditemukan']);
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

            // Commit transaksi
            if ($db->transStatus() === false) {
                // Rollback kalau ada yang gagal
                $db->transRollback();
                return json_encode(['status' => 'ERROR', 'message' => 'Terjadi kesalahan saat checkout']);
            }

            $db->transCommit();
            return json_encode([
                'status' => 'OK',
                'message' => 'berhasil checkout'
            ]);

        } catch (\Exception $e) {
            // Rollback kalau ada exception
            $db->transRollback();
            return json_encode(['status' => 'ERROR', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
}
