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
        $fromDate = $this->request->getGet('from_date');
        $toDate   = $this->request->getGet('to_date');

        // Jika salah satu kosong, atur default ke hari ini
        if (!$fromDate && !$toDate) {
            $fromDate = $toDate = date('Y-m-d');
        } elseif (!$fromDate) {
            $fromDate = $toDate;
        } elseif (!$toDate) {
            $toDate = $fromDate;
        }

        // Total Uang Masuk
        $incomeQuery = $this->financeModel->where('type', 'income')
                                        ->where('finance_date >=', $fromDate)
                                        ->where('finance_date <=', $toDate);
        $totalIncome = $incomeQuery->selectSum('amount')->first()->amount ?? 0;

        // Total Uang Keluar
        $expenseQuery = $this->financeModel->where('type', 'expense')
                                        ->where('finance_date >=', $fromDate)
                                        ->where('finance_date <=', $toDate);
        $totalExpense = $expenseQuery->selectSum('amount')->first()->amount ?? 0;

        // Semua transaksi
        $transactions = $this->financeModel
                            ->where('finance_date >=', $fromDate)
                            ->where('finance_date <=', $toDate)
                            ->orderBy('finance_date', 'DESC')
                            ->findAll();

        return view('admin/keuangan/index', [
            'title'        => 'Rekapan Keuangan',
            'from_date'    => $fromDate,
            'to_date'      => $toDate,
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
        // === LOGIKA PENGAMBILAN DATA SAMA PERSIS DENGAN FUNGSI INDEX() ===
        $fromDate = $this->request->getGet('from_date');
        $toDate   = $this->request->getGet('to_date');

        // Jika salah satu kosong, atur default ke hari ini
        if (!$fromDate && !$toDate) {
            // Jika tidak ada tanggal yang diberikan, gunakan tanggal default saat ini
            $fromDate = $toDate = date('Y-m-d');
        } elseif (!$fromDate) {
            // Jika fromDate kosong, gunakan toDate sebagai fromDate
            $fromDate = $toDate;
        } elseif (!$toDate) {
            // Jika toDate kosong, gunakan fromDate sebagai toDate
            $toDate = $fromDate;
        }

        // Total Uang Masuk
        $incomeQuery = $this->financeModel->where('type', 'income')
                                        ->where('finance_date >=', $fromDate)
                                        ->where('finance_date <=', $toDate);
        $totalIncome = $incomeQuery->selectSum('amount')->first()->amount ?? 0;

        // Total Uang Keluar
        $expenseQuery = $this->financeModel->where('type', 'expense')
                                         ->where('finance_date >=', $fromDate)
                                         ->where('finance_date <=', $toDate);
        $totalExpense = $expenseQuery->selectSum('amount')->first()->amount ?? 0;

        // Semua transaksi
        $transactions = $this->financeModel
                                ->where('finance_date >=', $fromDate)
                                ->where('finance_date <=', $toDate)
                                ->orderBy('finance_date', 'DESC')
                                ->findAll();

        $data = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'transactions' => $transactions,
            'nama_toko' => 'Coffee Shop Order', 
        ];

        // Load view HTML yang akan dikonversi ke PDF
        $html = view('admin/keuangan/ekspor', $data); 

        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        
        $dompdf = new Dompdf($options);
        
        $dompdf->loadHtml($html);

        // (Opsional) Set ukuran dan orientasi kertas
        $dompdf->setPaper('A4', 'portrait');

        // Render HTML to PDF
        $dompdf->render();

        // Output PDF ke browser
        $filename = 'data_keuangan_paras_selatan_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 0]); // 0 = buka di browser, 1 = download
        exit();
    }

}
