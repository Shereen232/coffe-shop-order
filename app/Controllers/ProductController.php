<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductBannerModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel, $productBannerModel, $cartModel, $cartItemModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->productBannerModel = new ProductBannerModel();
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

    public function banners()
    {
        $banners = $this->productBannerModel->findAll();

        return json_encode(['banners' => $banners]);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return ['status' => 'ERROR', 'message' => 'Keyword tidak boleh kosong'];
        }
        $products = $this->productModel->like('name', $keyword)->findAll();

        return json_encode(['products' => $products]);
    }

    public function getcart($id)
    {
        // Jika ID valid (bukan 0), ambil dari database
        if ($id != 0) {
            $cookie = $this->request->getCookie('cart');
            $cartCookie = json_decode($cookie, true);

            if ($cartCookie || !empty($cartCookie))  $this->storeCart($cartCookie);

            $carts = $this->cartModel
                ->select('carts.id, carts.session_id, carts.total, cart_items.id as items_id,cart_items.product_id, cart_items.qty, cart_items.subtotal, products.name, products.description')
                ->join('cart_items', 'cart_items.cart_id = carts.id', 'left')
                ->join('products', 'cart_items.product_id = products.id', 'left')
                ->where('carts.session_id', $id)
                ->findAll();

            return $this->response->setJSON(['status' => 'OK', 'carts' => $carts]);

        }

        // Kalau ID = 0, ambil cart dari cookie
        $request = service('request');
        $cookieCart = $request->getCookie('cart');
        $cart = json_decode($cookieCart, true);
        $cartWithDetail = [];

        if (!$cart || empty($cart)) {
            return $this->response->setJSON(['status' => 'OK', 'carts' => $cartWithDetail]);
        }

        foreach ($cart as $item) {
            $product = $this->productModel->find($item['product_id']);
            $cartWithDetail[] = [
                'product_id' => $item['product_id'],
                'qty' => $item['quantity'],
                'subtotal' => $item['quantity'] * ($product['price'] ?? 0),
                'name' => $product['name'] ?? 'Unknown',
                'description' => $product['description'] ?? '-',
                'total' => array_sum(array_column($cart, 'subtotal')),
            ];
        }

        return $this->response->setJSON(['status' => 'OK', 'carts' => $cartWithDetail]);
    }

    private function storeCart($cartCookie)
    {
        $this->cartModel->save([
            'session_id' => session('session')['table_id'],
            'total'      => array_sum(array_column($cartCookie, 'subtotal')),
        ]);
        $cartID = $this->cartModel->insertID();

        foreach ($cartCookie as $item) {
            $this->cartItemModel->save([
                'cart_id'     => $cartID,
                'product_id'  => $item['product_id'],
                'qty'         => $item['quantity'],
                'subtotal'    => $item['subtotal']
            ]);
        }

        $this->response->setCookie('cart', '', time() - 3600);
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
        $sessionID = session('session')['table_id'] ?? null;

        if (!$sessionID) {
            // Ambil cart yang sudah ada dari cookie (jika ada)
            $cartCookie = json_decode($this->request->getCookie('cart'), true) ?? [];
    
            // Cek apakah item sudah ada di dalam cart
            $found = false;
            foreach ($cartCookie as $item) {
                if ($item['product_id'] == $id) {
                    $item['quantity'] += $qty;
                    $found = true;
                    break;
                }

            }
    
            // Jika item belum ada, tambahkan item baru ke cart
            if (!$found) {
                $cartCookie[] = [
                    'product_id' => $id,
                    'quantity' => $qty,
                    'subtotal' => $harga * $qty
                ];
            }
    
            // Simpan cart yang diperbarui ke cookie (dalam format JSON)
            $response = service('response');
            $response->setCookie('cart', json_encode($cartCookie), 3600); // 1 jam
            return json_encode(['product' => $id, 'status' => 'OK']);
        }

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
        $sessionID = session('session')['table_id'] ?? null;
        if (!$sessionID) {
            $request = service('request');
            $response = service('response');

            // Ambil data cart dari cookie
            $cartCookie = json_decode($request->getCookie('cart'), true) ?? [];

            // Filter: hanya ambil item yang BUKAN product_id yg mau dihapus
            $updatedCart = array_filter($cartCookie, function($item) use ($id) {
                return $item['product_id'] != $id;
            });

            // Set ulang cookie dengan cart yang sudah difilter
            $response->setCookie('cart', json_encode(array_values($updatedCart)), 3600); // 1 jam

            // Calculate the new total price of the cart
            $totalHarga = array_sum(array_column($updatedCart, 'subtotal') ?? []);

            return $response->setJSON(['status' => 'success', 'message' => 'Item removed from cart', 'new_total' => $totalHarga]);
        }

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
