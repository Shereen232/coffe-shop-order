<?php 


namespace App\Controllers\Admin; // Sesuaikan namespace jika berbeda

use App\Controllers\BaseController;
use App\Models\OrderModel; // Asumsikan Anda memiliki OrderModel
use App\Models\OrderItemModel; // Asumsikan Anda memiliki OrderItemModel
use App\Models\PaymentModel; // Asumsikan Anda memiliki PaymentModel
use App\Models\TableModel; // Asumsikan Anda memiliki TableModel (untuk join ke tabel)

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $paymentModel;
    protected $tableModel; // Jika digunakan secara langsung di controller

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel = new PaymentModel();
        $this->tableModel = new TableModel(); // Inisialisasi TableModel jika diperlukan
    }

    public function index()
    {
        // 1. Mengambil semua data pesanan (orders)
        // Join dengan tabel 'tables' untuk mendapatkan nomor meja
        // Pastikan 'orders.user_id' adalah kolom yang berelasi dengan 'tables.id' (atau 'tables.table_id' jika nama kolomnya berbeda)
        // Perhatikan bahwa di sini Anda join 'orders.user_id' ke 'tables.id'. Pastikan 'user_id' di tabel 'orders'
        // benar-benar merujuk ke ID dari tabel (misalnya, jika user_id adalah id dari user yang memesan,
        // dan user tersebut dikaitkan dengan meja, maka relasinya benar. Jika orders.table_id yang merujuk ke meja,
        // maka join harus diubah menjadi orders.table_id = tables.id).
        $orders = $this->orderModel
                     ->select('orders.*, tables.table_number') // Select orders.* untuk semua kolom order, dan table_number dari tabel
                     ->join('tables', 'orders.user_id = tables.id') // Perbaiki jika relasi berbeda
                     ->findAll();

        // 2. Mengambil semua item pesanan (order_items) dan menggabungkannya dengan produk
        $orderItems = $this->orderItemModel
                           ->select('order_items.*, products.name AS product_name, products.image, products.price')
                           ->join('products', 'products.id = order_items.product_id')
                           ->findAll();

        // 3. Mengelompokkan item pesanan berdasarkan order_id
        $groupedItems = [];
        foreach ($orderItems as $item) {
            $groupedItems[$item->order_id][] = $item;
        }

        // 4. Menambahkan item pesanan ke setiap objek pesanan
        foreach ($orders as $order) {
            $order->items = $groupedItems[$order->id] ?? [];
            // Ambil informasi pembayaran untuk setiap pesanan (jika ada relasi one-to-one atau one-to-many)
            // Asumsi: Ada kolom order_id di tabel payments atau ada cara lain untuk mengaitkan payment ke order
            $order->payment = $this->paymentModel->where('order_id', $order->id)->first(); // Ambil payment pertama yang terkait dengan order ini
        }

        // 5. Mengambil semua data pembayaran (jika Anda ingin menampilkannya secara terpisah atau dalam konteks lain)
        // Jika Anda sudah mengaitkan payment ke setiap order, baris ini mungkin tidak lagi diperlukan
        // $payments = $this->paymentModel->findAll(); 

        $data['title'] = 'Data Pesanan Produk'; // Judul halaman
        $data['orders'] = $orders; // Data pesanan yang sudah digabungkan dengan item dan info meja/pembayaran
        // $data['payments'] = $payments; // Hanya sertakan jika Anda ingin mengakses payments secara terpisah

        return view('admin/order/index', $data); // Menampilkan data di view admin/order/index
    }

    // Fungsi baru untuk mendapatkan detail pesanan via AJAX
    public function get_detail($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID Pesanan tidak ditemukan.'
            ]);
        }

        $order = $this->orderModel
                      ->select('orders.*, tables.table_number')
                      ->join('tables', 'orders.user_id = tables.id') // Sesuaikan relasi jika berbeda
                      ->find($id);

        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ]);
        }

        // Ambil item pesanan yang terkait dengan pesanan ini
        $order->items = $this->orderItemModel
                              ->select('order_items.*, products.name AS product_name, products.image, products.price')
                              ->join('products', 'products.id = order_items.product_id')
                              ->where('order_id', $order->id)
                              ->findAll();

        // Ambil informasi pembayaran
        $order->payment = $this->paymentModel->where('order_id', $order->id)->first();

        // Kembalikan data dalam format JSON
        return $this->response->setJSON([
            'success' => true,
            'data' => $order
        ]);
    }
}