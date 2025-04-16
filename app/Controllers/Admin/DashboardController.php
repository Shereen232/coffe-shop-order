<?php

namespace App\Controllers\Admin;

use App\Models\ReviewModel;

class DashboardController extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }
    
    public function index(): string
    {
        $db = \Config\Database::connect();
        $totalViews = $db->table('view_counter')
        ->where('id', 1)
        ->get()
        ->getRow()
        ->total_views;
        $products = $db->table('products')
        ->select('COUNT(*) as total_products');
        $reviews = $this->reviewModel
        ->orderBy('created_at', 'DESC')
        ->findAll(5);

        $data['title'] = 'Dashboard Admin';
        $data['total_views'] = $totalViews;
        $data['total_products'] = $products->get()->getRow()->total_products;
        $data['total_orders'] = 0;
        $data['total_payments'] = 0;
        $data['reviews'] = $reviews;
        return view('admin/index.php', $data);
    }
}
