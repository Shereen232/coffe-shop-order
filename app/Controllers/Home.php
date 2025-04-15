<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ReviewModel;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $ip = $this->request->getIPAddress();
        $today = date('Y-m-d');

        $exists = $db->table('website_views')
            ->where('ip_address', $ip)
            ->where('DATE(viewed_at)', $today)
            ->countAllResults();

        if ($exists == 0) {
            // Tambah 1 ke total_views
            $db->table('view_counter')
                ->where('id', 1)
                ->set('total_views', 'total_views+1', false)
                ->update();

            // Tambahkan log kunjungan (opsional)
            $db->table('website_views')->insert([
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
            ]);
        }

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
