<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('customer/index.php');
    }

    public function show($id)
    {
        // Ambil data produk berdasarkan ID
        $productModel = new ProductModel();
        $data['title'] = 'Deskripsi Produk';
        $data['product'] = $productModel->asObject()->find($id);
        return view('customer/food/index.php', $data);
    }
}
