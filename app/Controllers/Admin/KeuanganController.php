<?php 

namespace App\Controllers\Admin;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class KeuanganController extends BaseController
{
    protected $orderModel, $orderItemModel, $paymentModel, $financeModel;
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel = new PaymentModel();
        $this->financeModel = new \App\Models\FinanceModel();
    }


    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        // Default: Hari ini
        if (!$startDate && !$endDate) {
            $startDate = $endDate = date('Y-m-d');
        } elseif (!$startDate) {
            $startDate = $endDate;
        } elseif (!$endDate) {
            $endDate = $startDate;
        }

        // Tambahkan waktu agar mencakup 1 hari penuh
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime   = $endDate . ' 23:59:59';

        // Total Income
        $totalIncome = $this->financeModel
            ->where('type', 'income')
            ->where('finance_date >=', $startDateTime)
            ->where('finance_date <=', $endDateTime)
            ->selectSum('amount')
            ->first()->amount ?? 0;

        // Total Expense
        $totalExpense = $this->financeModel
            ->where('type', 'expense')
            ->where('finance_date >=', $startDateTime)
            ->where('finance_date <=', $endDateTime)
            ->selectSum('amount')
            ->first()->amount ?? 0;

        // Transaksi
        $transactions = $this->financeModel
            ->where('finance_date >=', $startDateTime)
            ->where('finance_date <=', $endDateTime)
            ->orderBy('finance_date', 'DESC')
            ->findAll();

        return view('admin/keuangan/index', [
            'title'        => 'Rekapan Keuangan',
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'totalIncome'  => $totalIncome,
            'totalExpense' => $totalExpense,
            'transactions' => $transactions,
        ]);
    }



    public function storeExpense()
    {
        $data = $this->request->getPost([
            'type',          // fixed = 'expense'
            'notes',
            'amount',
            'finance_date',
        ]);

        // Validasi sederhana
        if (!$data['finance_date'] || !$data['amount']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Deskripsi dan jumlah wajib diisi.'
            ]);
        }

        $this->financeModel->insert($data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data pengeluaran berhasil disimpan.'
        ]);
    }

    public function exportPdf()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        // Default: hari ini jika tidak dipilih
        if (!$startDate && !$endDate) {
            $startDate = $endDate = date('Y-m-d');
        } elseif (!$startDate) {
            $startDate = $endDate;
        } elseif (!$endDate) {
            $endDate = $startDate;
        }

        // Tambahkan waktu untuk filter full hari
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime   = $endDate . ' 23:59:59';

        // Uang Masuk
        $totalIncome = $this->financeModel
            ->where('type', 'income')
            ->where('finance_date >=', $startDateTime)
            ->where('finance_date <=', $endDateTime)
            ->selectSum('amount')
            ->first()->amount ?? 0;

        // Uang Keluar
        $totalExpense = $this->financeModel
            ->where('type', 'expense')
            ->where('finance_date >=', $startDateTime)
            ->where('finance_date <=', $endDateTime)
            ->selectSum('amount')
            ->first()->amount ?? 0;

        // Transaksi
        $transactions = $this->financeModel
            ->where('finance_date >=', $startDateTime)
            ->where('finance_date <=', $endDateTime)
            ->orderBy('finance_date', 'DESC')
            ->findAll();

        $data = [
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'totalIncome'  => $totalIncome,
            'totalExpense' => $totalExpense,
            'transactions' => $transactions,
            'nama_toko'    => 'Coffee Shop Order',
        ];

        // Render HTML dari view
        $html = view('admin/keuangan/ekspor', $data);

        // Inisialisasi Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream PDF ke browser
        $filename = 'laporan_keuangan_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 0]);
    }

}
