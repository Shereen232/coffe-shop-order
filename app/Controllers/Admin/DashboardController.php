<?php

namespace App\Controllers\Admin;

class DashboardController extends BaseController
{
    public function index(): string
    {
        return view('admin/index.php');
    }
}
