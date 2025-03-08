<?php

namespace App\Controllers\Admin;

use App\Models\TableModel;
use CodeIgniter\Controller;

class TableController extends Controller
{
    protected $tableModel;

    public function __construct()
    {
        $this->tableModel = new TableModel();
    }

    public function index()
    {
        $data['tables'] = $this->tableModel->getTables();
        return view('admin/tables/index', $data);
    }

    public function create()
    {
        return view('admin/tables/create');
    }

    public function store()
    {
        $this->tableModel->insert([
            'table_number' => $this->request->getPost('table_number'),
            'status'       => $this->request->getPost('status'),
        ]);

        return redirect()->to('/tables')->with('success', 'Meja berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data['table'] = $this->tableModel->getTableById($id);
        return view('admin/tables/edit', $data);
    }

    public function update($id)
    {
        $this->tableModel->update($id, [
            'table_number' => $this->request->getPost('table_number'),
            'status'       => $this->request->getPost('status'),
        ]);

        return redirect()->to('/tables')->with('success', 'Meja berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->tableModel->delete($id);
        return redirect()->to('/tables')->with('success', 'Meja berhasil dihapus!');
    }
}
