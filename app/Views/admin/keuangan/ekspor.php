<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Paras Selatan</title>
    <style>
        /* Desain CSS untuk PDF */
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 10pt;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .header img {
            position: absolute;
            left: 0;
            top: 0;
            max-height: 40px; /* Sesuaikan ukuran logo */
        }
        .header h1 {
            font-size: 18pt;
            margin-bottom: 5px;
            color: #212529;
        }
        .header h2 {
            font-size: 14pt;
            margin-top: 0;
            color: #495057;
        }
        .date-range {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 9pt;
            color: #6c757d;
        }

        /* Summary Section */
        .summary-cards {
            display: table; /* Menggunakan display table untuk layout 3 kolom di PDF */
            width: 100%;
            border-spacing: 10px; /* Jarak antar kolom */
            margin-bottom: 30px;
        }
        .summary-card-item {
            display: table-cell;
            width: 33.33%; /* Bagi rata 3 kolom */
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .summary-card-item h6 {
            margin-top: 0;
            margin-bottom: 5px;
            color: #6c757d;
            font-size: 10pt;
        }
        .summary-card-item h4 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }
        .text-success { color: #198754; } /* Green */
        .text-danger { color: #dc3545; }  /* Red */
        .text-primary { color: #0d6efd; } /* Blue */

        /* Table Section */
        .table-title {
            font-size: 12pt;
            margin-bottom: 15px;
            font-weight: bold;
            color: #212529;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: top;
            font-size: 9pt;
        }
        table th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
        }
        table td.text-end {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .bg-success { background-color: #198754; }
        .bg-danger { background-color: #dc3545; }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 8pt;
            color: #6c757d;
            border-top: 1px solid #eee;
            padding-top: 10px;
            position: fixed;
            bottom: 20px;
            width: 90%;
            left: 5%;
            right: 5%;
        }
        .footer .left { float: left; }
        .footer .right { float: right; }
        .footer .center { display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php
            // Asumsi logo ada di public/assets/img/logo.png
            // Pastikan path ini benar dan dapat diakses oleh library PDF
            $logoPath = FCPATH . 'assets/img/logo_coffee_shop.png'; // Ganti dengan path logo Anda yang sebenarnya
            if (file_exists($logoPath)) {
                echo '<img src="' . $logoPath . '" alt="Logo Coffee Shop">';
            }
            ?>
            <h1>Laporan Keuangan</h1>
            <h2>Data Keuangan Paras Selatan</h2>
            <span class="date-range">Periode: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></span>
        </div>

        <div class="summary-cards">
            <div class="summary-card-item">
                <h6>Total Uang Masuk</h6>
                <h4 class="text-success">Rp <?= number_format($totalIncome, 0, ',', '.') ?></h4>
            </div>
            <div class="summary-card-item">
                <h6>Total Uang Keluar</h6>
                <h4 class="text-danger">Rp <?= number_format($totalExpense, 0, ',', '.') ?></h4>
            </div>
            <div class="summary-card-item">
                <h6>Saldo Bersih</h6>
                <h4 class="text-primary">Rp <?= number_format($totalIncome - $totalExpense, 0, ',', '.') ?></h4>
            </div>
        </div>

        <div class="table-title">Detail Transaksi</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Notes</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)) : ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #6c757d;">Tidak ada transaksi.</td>
                    </tr>
                <?php else : $no = 1; foreach ($transactions as $t) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d M Y', strtotime($t->finance_date)) ?></td>
                        <td>
                            <span class="badge bg-<?= $t->type === 'income' ? 'success' : 'danger' ?>">
                                <?= $t->type === 'income' ? 'Uang Masuk' : 'Uang Keluar' ?>
                            </span>
                        </td>
                        <td><?= esc($t->notes) ?></td>
                        <td class="text-end">
                            <span class="badge bg-<?= $t->type === 'income' ? 'success' : 'danger' ?>">
                                Rp <?= number_format($t->amount, 0, ',', '.') ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <span class="left">2025 &copy; COFFEE SHOP ORDER</span>
        <span class="right">Laporan Dibuat: <?= date('d M Y, H:i') ?> WIB</span>
        </div>
</body>
</html>