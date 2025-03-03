<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;
    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data['cat'] = request()->getVar('name');
        $data['products'] = $this->productModel->findAll();

        return json_encode($data);
    }
    
}
