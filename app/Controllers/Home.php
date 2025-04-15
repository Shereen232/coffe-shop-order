<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ReviewModel;

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
        $reviewModel = new ReviewModel();
        $data['title'] = 'Deskripsi Produk';
        $data['product'] = $productModel->asObject()->find($id);
        $data['reviews'] = $reviewModel->asObject()->where('product_id', $id)->findAll();
        return view('customer/food/index.php', $data);
    }

    public function contactUs(): string
    {
        $data['title'] = 'Kontak Kami';
        return view('customer/contact_us.php', $data);
    }

    public function faq(): string
    {
        $data['title'] = 'FAQ';
        return view('customer/faq.php', $data);
    }
}
