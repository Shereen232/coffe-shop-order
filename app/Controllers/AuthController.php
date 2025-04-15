<?php

namespace App\Controllers;

class AuthController extends BaseController
{

    public function login(): string
    {
        $data['title'] = 'Deskripsi Produk';
        return view('customer/auth/login.php', $data);
    }
}
