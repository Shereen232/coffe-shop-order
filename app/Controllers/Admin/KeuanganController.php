<?php 

namespace App\Controllers\Admin;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;

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
        $date = $this->request->getGet('date');

        // Total Uang Masuk
        $incomeQuery = $this->financeModel->where('type', 'income');
        if ($date) {
            $incomeQuery->where("DATE(finance_date)", $date);
        }
        $totalIncome = $incomeQuery->selectSum('amount')->first()->amount ?? 0;

        // Total Uang Keluar
        $expenseQuery = $this->financeModel->where('type', 'expense');
        if ($date) {
            $expenseQuery->where("DATE(finance_date)", $date);
        }
        $totalExpense = $expenseQuery->selectSum('amount')->first()->amount ?? 0;

        // Semua transaksi (baik income maupun expense)
        $transQuery = $this->financeModel;
        if ($date) {
            $transQuery = $transQuery->where("DATE(finance_date)", $date);
        }
        $transactions = $transQuery->orderBy('finance_date', 'DESC')->findAll();


        return view('admin/keuangan/index', [
            'title'        => 'Rekapan Keuangan',
            'date'         => $date,
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

}
