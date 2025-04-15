<?php

namespace App\Controllers\Admin;

use App\Models\TableModel;
use CodeIgniter\Controller;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

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

        return redirect()->to('admin/tables')->with('success', 'Meja berhasil ditambahkan!');
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

        return redirect()->to('admin/tables')->with('success', 'Meja berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->tableModel->delete($id);
        return redirect()->to('admin/tables')->with('success', 'Meja berhasil dihapus!');
    }

    public function generateQr($tableNumber)
    {
        if (!$this->tableModel->where('table_number', $tableNumber)->first()) {
            return redirect()->to('/')->with('error', 'Kode meja tidak valid');
        }
        // Buat QR code
        $url = base_url('auth/login/' . $tableNumber); // URL tujuan dari QR code

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url, // <-- ini dia URL yang akan ditanam di QR
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: FCPATH . 'images/logo.jpg',
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();

        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setBody($result->getString());
    }
}
