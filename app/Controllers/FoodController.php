<?php

namespace App\Controllers;

class FoodController extends BaseController
{
    public function index(): string
    {
        $data['cat'] = request()->getVar('name');
        return view('customer/food/index.php', $data);
    }
}
