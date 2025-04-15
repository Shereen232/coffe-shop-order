<?php

namespace App\Controllers\Admin;

use App\Models\CartModel;
use App\Models\TableModel;

class AuthController extends BaseController
{
    protected $tableModel, $cartModel, $cartItemModel;

    public function __construct()
    {
        $this->tableModel = new TableModel();
        $this->cartModel = new CartModel();
    }

    public function login(): string
    {
        $data['title'] = 'Login';
        return view('admin/auth/login.php', $data);
    }

    public function dologin()
    {
        return redirect()->to('admin')->with('success', 'Berhasil login!');
    }

    public function logout()
    {
        // Redirect ke halaman login setelah logout
        return redirect()->to('admin/auth/login')->with('success', 'Berhasil logout!');
    }
}
