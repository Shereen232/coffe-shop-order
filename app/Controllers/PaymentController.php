<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use App\Models\TableModel;

class PaymentController extends BaseController
{
    protected $productModel, $cartModel, $cartItemModel, $orderModel, $orderItemModel, $tableModel, $paymentModel, $financeModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->tableModel = new TableModel();
        $this->paymentModel = new PaymentModel();
        $this->financeModel = new \App\Models\FinanceModel();
    }

    public function updateStatus()
    {
        $data = $this->request->getJSON();

        // Validasi data wajib
        if (empty($data->order_id) || empty($data->transaction_status)) {
            return $this->response->setJSON([
                'status' => 'failed',
                'message' => 'Invalid data'
            ])->setStatusCode(400);
        }
        
        $orderId = (int) $data->order_id;

        // Update status order jadi "processing"
        $order = $this->orderModel->find($orderId);
        if ($order) {
            $this->orderModel->update($orderId, ['status' => 'processing']);
        }

        // Update status pembayaran
        $this->paymentModel
            ->where('order_id', $orderId)
            ->set(['payment_status' => $data->transaction_status])
            ->update();

        // Catat keuangan hanya jika status settlement
        if ($data->transaction_status === 'settlement') {
            $this->financeModel->insert([
                'order_id'     => $orderId,
                'type'         => 'income',
                'amount'       => $data->gross_amount ?? 0,
                'finance_date' => date('Y-m-d H:i:s'),
                'notes'        => 'Pembayaran online untuk order ID ' . $orderId,
            ]);

            // Kurangi stok produk
            $orderItems = $this->orderItemModel
                ->where('order_id', $orderId)
                ->findAll();

            foreach ($orderItems as $item) {
                $this->productModel
                    ->where('id', $item->product_id)
                    ->set('stock', 'stock - ' . (int) $item->quantity, false)
                    ->update();
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }

}
