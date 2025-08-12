<?php

namespace App\Controllers\Admin;

use App\Models\ProductModel;
use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\ProductReviewModel;

class DashboardController extends BaseController
{
    protected $productreviewModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->productreviewModel = new ProductReviewModel();
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
        $reviews = $this->productreviewModel
        ->join('orders', 'orders.id = product_reviews.order_id')->asObject()
        ->orderBy('orders.created_at', 'DESC')
        ->findAll(5);

        $latestOrders = $db->table('orders')
        ->join('tables', 'tables.id = orders.user_id')
        ->join('payments', 'payments.order_id = orders.id')
        ->orderBy('orders.created_at', 'DESC')
        ->limit(5)
        ->get();


        $data['title'] = 'Dashboard Admin';
        $data['total_views'] = $totalViews;
        $data['total_products'] = $products->get()->getRow()->total_products;
        $data['total_orders'] = $db->table('orders')->countAll(); // <-- Perbaiki di sini
        $data['total_payments'] = $db->table('payments')->countAll();
        $data['latest_orders'] = $latestOrders->getResult();
        $data['reviews'] = $reviews;
        return view('admin/index.php', $data);
    }
}
