<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel, $cartModel, $cartItemModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
    }

    public function index()
    {
        $category = $this->request->getGet('category');

        if ($category) {
            $products = $this->productModel->where('category_id', $category)->findAll();
        } else {
            $products = $this->productModel->findAll();
        }

        return json_encode(['products' => $products]);
    }

    public function getcart($id)
    {
        $carts = $this->cartModel
            ->select('carts.id, carts.session_id, carts.total, cart_items.product_id, cart_items.qty, cart_items.subtotal, products.name, products.description')
            ->join('cart_items', 'cart_items.cart_id = carts.id', 'left')
            ->join('products', 'cart_items.product_id = products.id', 'left')
            ->where('carts.session_id', $id)
            ->findAll();

        return json_encode(['carts' => $carts]);
    }

    public function addcart($id)
    {
        // Ambil data produk
        $product = $this->productModel->asObject()->find($id);
        if (!$product) {
            return json_encode(['status' => 'ERROR', 'message' => 'Produk tidak ditemukan']);
        }

        $harga = $product->price;
        $qty = 1;
        $sessionID = 151515;

        // Cek apakah cart sudah ada
        $cart = $this->cartModel->where('session_id', $sessionID)->first();
        
        if (!$cart) {
            $this->cartModel->save([
                'session_id' => $sessionID,
                'total'      => $harga * $qty,
            ]);
            $cartID = $this->cartModel->insertID();
            $cart = $this->cartModel->find($cartID); // Ambil cart sebagai object
        }

        // Tambahkan item ke dalam cart
        $this->cartItemModel->save([
            'cart_id'     => $cart->id,
            'product_id'  => $id,
            'qty'         => $qty,
            'subtotal'    => $harga * $qty
        ]);

        // Hitung ulang total harga dalam cart
        $carts = $this->cartItemModel->where('cart_id', $cart->id)->findAll();
        $total = 0;
        foreach ($carts as $cartItem) {
            $total += $cartItem->subtotal;
        }

        // Update total di tabel carts
        $this->cartModel->update($cart->id, ['total' => $total]);

        return json_encode(['product' => $id, 'status' => 'OK']);
    }
    
}
