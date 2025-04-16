<?php 

namespace App\Controllers\Admin;

use App\Models\OrderItemModel;
use App\Models\OrderModel;

class OrderController extends BaseController
{
    protected $orderModel, $orderItemModel;
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }
    public function index()
    {
        $orders = $this->orderModel->join('tables', 'orders.user_id = tables.id')->findAll();  // Mengambil semua data kategori produk
        $orderItems = $this->orderItemModel
        ->select('order_items.*, products.name AS product_name, products.image, products.price')
        ->join('products', 'products.id = order_items.product_id')
        ->findAll();
        $groupedItems = [];
        foreach ($orderItems as $item) {
            $groupedItems[$item->order_id][] = $item;
        }
        foreach ($orders as $order) {
            $order->items = $groupedItems[$order->id] ?? [];
        }        
        $data['title'] = 'Order';  // Judul halaman
        $data['orders'] = $orders;  // Mengambil semua data kategori produk
        return view('admin/order/index', $data);  // Menampilkan kategori produk di view
    }

}
