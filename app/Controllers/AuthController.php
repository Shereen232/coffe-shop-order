<?php

namespace App\Controllers;

use App\Models\TableModel;

class AuthController extends BaseController
{
    protected $tableModel;

    public function __construct()
    {
        $this->tableModel = new TableModel();
    }

    public function login(): string
    {
        $data['title'] = 'Deskripsi Produk';
        return view('customer/auth/login.php', $data);
    }

    public function dologin($kode)
    {
        // Validasi
        $table = $this->tableModel->where('table_number', $kode)->first();
        if (!$table || $table['status'] !== 'available') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Credential tidak dikenali');
        }

        // Ambil data device
        $ip = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent();
        $device = $userAgent->getAgentString();

        $db = \Config\Database::connect();

        $builder = $db->table('login_histories');

        $login_id = uniqid('login_', true);
        $data = [
            'table_id'     => $table['id'],
            'login_id'     => $login_id,
            'device'       => $device,
            'user_agent'   => $userAgent,
            'ip_address'   => $ip,
            'logged_in_at' => date('Y-m-d H:i:s'),
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        // Simpan kode ke session
        session()->set('session', $data);

        $builder->insert($data);

        $table['status'] = 'occupied';
        $this->tableModel->update($table['id'], $table);

        return redirect()->to('/')->with('success', 'Berhasil login!');
    }

    public function logout()
    {
        // Validasi
        $table = $this->tableModel->find(session('session')['table_id']);
        if (!$table) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Credential tidak dikenali');
        }

        $table['status'] = 'available';
        $this->tableModel->update($table['id'], $table);
        session()->destroy();

        // Redirect ke halaman login setelah logout
        return redirect()->to('/');
    }
}
