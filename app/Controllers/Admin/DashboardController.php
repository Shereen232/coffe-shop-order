<?php

namespace App\Controllers\Admin;

class DashboardController extends BaseController
{
    public function index(): string
    {
        return view('admin/index.php');
    }

    public function myAccount(): string
    {
        $data['user'] = [
            'nama_lengkap' => 'Aulia Rahma',
            'email' => 'aulia@example.com',
            'username' => 'aulia.rahma',
            'role' => 'Admin',
            'created_at' => '2024-01-12'
        ];

        return view('admin/my_account.php', $data);
    }
}
