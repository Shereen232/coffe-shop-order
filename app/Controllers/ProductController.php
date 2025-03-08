<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel, $chartModel, $chartItemModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->chartModel = new CartModel();
        $this->chartItemModel = new CartItemModel();
    }

    public function index()
    {
        $data['cat'] = request()->getVar('name');
        $data['products'] = $this->productModel->findAll();

        return json_encode($data);
    }

    public function addchart($id)
    {
        $harga = 10000;
        $qty = 2;
        $this->chartModel->save([
            'session_id' => 151515,
            'total' => $harga*$qty,
        ]);

        $this->chartItemModel->save([
            'chart_id'      => $this->chartModel->getInsertID(),
            'product_id'    => $id,
            'qty'           => $qty,
            'subtotal'      => $harga*$qty
        ]);

        return json_encode(['product' => $id, 'status' => 'OK']);
    }
    
}
