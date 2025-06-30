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
    protected $productModel, $cartModel, $cartItemModel, $orderModel, $orderItemModel, $tableModel, $paymentModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->tableModel = new TableModel();
        $this->paymentModel = new PaymentModel();
    }

    public function updateStatus()
    {

        $orderModel = new \App\Models\OrderModel();
        $paymentModel = new \App\Models\PaymentModel();

        $data = $this->request->getJSON();

        // Cek jika data tersedia
        if (!isset($data->order_id) || !isset($data->transaction_status)) {
            return $this->response->setJSON(['status' => 'failed', 'message' => 'Invalid data'])->setStatusCode(400);
        }

        // Update status order
        $order = $orderModel->find($data->order_id);
        if ($order) {
            $orderModel->update($data->order_id, ['status' => 'processing']);
        }

        // Update status pembayaran
        $paymentModel->where('order_id', $data->order_id)
            ->set(['payment_status' => $data->transaction_status])
            ->update();

        return $this->response->setJSON(['status' => 'success']);

    }
}
