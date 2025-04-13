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

        return json_encode(['products' => $products, 'category' => $category]);
    }

    public function getcart($id)
    {
        $carts = $this->cartModel
            ->select('carts.id, carts.session_id, carts.total, cart_items.id as items_id,cart_items.product_id, cart_items.qty, cart_items.subtotal, products.name, products.description')
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
        $qty = $this->request->getPost('qty');
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
        $totalHarga = $this->cartItemModel->selectSum('subtotal')->where('cart_id', $cart->id)->get()->getRow()->subtotal ?? 0;
        // Update total di tabel carts
        $this->cartModel->update($cart->id, ['total' => $totalHarga]);

        return json_encode(['product' => $id, 'status' => 'OK']);
    }

    public function deleteItem($id)
    {
        $sessionID = 151515;
        $cart = $this->cartModel->where('session_id', $sessionID)->first();

        if (!$cart) {
            return $this->response->setJSON(['status' => 'ERR', 'message' => 'Cart not found']);
        }

        $cartItem = $this->cartItemModel->where('cart_id', $cart->id)->where('id', $id)->first();

        if (!$cartItem) {
            return $this->response->setJSON(['status' => 'ERR', 'message' => 'Item not found']);
        }

        $this->cartItemModel->delete($cartItem->id);

        // Hitung ulang total harga
        $totalHarga = $this->cartItemModel->selectSum('subtotal')->where('cart_id', $cart->id)->get()->getRow()->subtotal ?? 0;

        // Update total harga di tabel cart
        $this->cartModel->update($cart->id, ['total' => $totalHarga]);

        return $this->response->setJSON([
            'status' => 'OK',
            'message' => 'Item deleted successfully',
            'new_total' => $totalHarga
        ]);
    }
    
}
