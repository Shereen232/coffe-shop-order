<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Paras Selatan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 10pt;
        }
        .container { width: 90%; margin: 20px auto; }

        /* HEADER */
        .header { text-align: center; margin-bottom: 15px; position: relative; }
        .header img { position: absolute; left: 0; top: 0; max-height: 80px; } /* logo diperbesar */
        .header h1 { font-size: 20pt; margin: 0; font-weight: bold; }
        .header h2 { font-size: 14pt; margin: 2px 0; font-weight: normal; }
        .header p { margin: 0; font-size: 10pt; color: #555; }
        .header .line {
            border-top: 2px solid #000;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        /* SUMMARY CARDS */
        .summary-cards { display: table; width: 100%; border-spacing: 10px; margin-bottom: 30px; }
        .summary-card-item {
            display: table-cell;
            width: 33.33%;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .summary-card-item h6 { margin: 0 0 5px; font-size: 10pt; color: #6c757d; }
        .summary-card-item h4 { margin: 0; font-size: 14pt; font-weight: bold; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #0d6efd; }

        /* TABLE */
        .table-title { font-size: 12pt; margin-bottom: 10px; font-weight: bold; color: #212529; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }


        table th, table td { border: 1px solid #dee2e6; padding: 6px 8px; font-size: 9pt; }
        table th { background-color: #e9ecef; font-weight: bold; text-align: center; }
        table td.text-end { text-align: right; }
        .badge { padding: 0.3em 0.6em; font-size: 0.75em; font-weight: 700; color: #fff; border-radius: 0.25rem; }
        .bg-success { background-color: #198754; }
        .bg-danger { background-color: #dc3545; }

        /* FOOTER */
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
        }
        .footer .left { float: left; }
        .footer .right { float: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php
            $logoPath = FCPATH . 'images/logo.jpg'; 
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoSrc = 'data:image/jpeg;base64,' . $logoData;
                echo '<img src="' . $logoSrc . '" alt="Logo">';
            }
            ?>
            <h1>PARAS SELATAN</h1>
            <h2>Laporan Rekap KAS Pembayaran</h2>
            <div class="line"></div>
            <h2 class="date-range">Periode: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></h2>
        </div>

        <!-- <div class="summary-cards">
            <div class="summary-card-item">
                <h6>Total Uang Masuk</h6>
                <h4 class="text-success">Rp <?= number_format($totalIncome, 0, ',', '.') ?></h4>
            </div>
            <div class="summary-card-item">
                <h6>Total Uang Keluar</h6>
                <h4 class="text-danger">Rp <?= number_format($totalExpense, 0, ',', '.') ?></h4>
            </div>
            <div class="summary-card-item">
                <h6>Sisa Kas</h6>
                <h4 class="text-primary">Rp <?= number_format($totalIncome - $totalExpense, 0, ',', '.') ?></h4>
            </div>
        </div> -->

        <div class="table-title">Detail Transaksi</div>
        <table>
            <thead style="border-bottom: 2px solid #000;">
                <tr>
                    <th style="width: 3%;">#</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">TRX ID</th>
                    <th style="width: 5%;">Jenis</th>
                    <th style="width: 15%;">Catatan</th>
                    <th style="width: 25%;">Produk</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 3%;">Jumlah</th>
                    <th style="width: 20%;">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)) : ?>
                    <tr>
                        <td colspan="8" style="text-align:center; color:#6c757d;">Tidak ada transaksi.</td>
                    </tr>
                <?php else : $no = 1; $grandTotal = 0; ?>
                    <?php foreach ($transactions as $t) : ?>
                        <?php $grandTotal += $t->amount; ?>
                        <?php if (!empty($t->items)) : ?>
                            <?php foreach ($t->items as $idx => $item): ?>
                                <tr>
                                    <?php if ($idx === 0): ?>
                                        <td rowspan="<?= count($t->items) ?>"><?= $no++ ?></td>
                                        <td rowspan="<?= count($t->items) ?>"><?= date('d M Y', strtotime($t->finance_date)) ?></td>
                                        <td rowspan="<?= count($t->items) ?>"><?= $t->transaction_id ?></td>
                                        <td rowspan="<?= count($t->items) ?>" style="text-align:center;">
                                            <span class="badge bg-<?= $t->type === 'income' ? 'success' : 'danger' ?>">
                                                <?= $t->type === 'income' ? 'Masuk' : 'Keluar' ?>
                                            </span>
                                        </td>
                                        <td rowspan="<?= count($t->items) ?>"><?= esc($t->notes) ?></td>
                                    <?php endif; ?>
                                    <td><?= esc($item->product_name) ?></td>
                                    <td class="text-end">Rp <?= number_format($item->price, 0, ',', '.') ?></td>
                                    <td class="text-end"><?= esc($item->quantity) ?></td>
                                    <?php if ($idx === 0): ?>
                                        <td rowspan="<?= count($t->items) ?>" class="text-end">
                                            Rp <?= number_format($t->amount, 0, ',', '.') ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d M Y', strtotime($t->finance_date)) ?></td>
                                <td>-</td>
                                <td style="text-align:center;">
                                    <span class="badge bg-<?= $t->type === 'income' ? 'success' : 'danger' ?>">
                                        <?= $t->type === 'income' ? 'Masuk' : 'Keluar' ?>
                                    </span>
                                </td>
                                <td><?= esc($t->notes) ?></td>
                                <td colspan="3" style="text-align:center; color:#6c757d;">Tidak ada item</td>
                                <td class="text-end">Rp <?= number_format($t->amount, 0, ',', '.') ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <!-- TOTAL -->
                    <tr>
                        <td colspan="8" style="text-align:right; font-weight:bold; background:#f1f1f1;">TOTAL</td>
                        <td class="text-end" style="font-weight:bold; background:#f1f1f1;">
                            Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- AREA TANDA TANGAN -->
                <!-- AREA TANDA TANGAN -->
        <div style="width:100%; margin-top:50px;">
            <!-- MENGETAHUI / OWNER -->
            <div style="float:left; width:40%; text-align:center;">
                <p>Mengetahui,</p>
                <br><br><br><br>
                <p><strong>OWNER</strong></p>
            </div>

            <!-- YANG MEMBUAT / USER LOGIN -->
            <div style="float:right; width:40%; text-align:center;">
                <p>Yang Membuat,</p>
                <br><br><br><br>
                <p><strong><?= esc($user->name) ?></strong></p>
            </div>

            <div style="clear:both;"></div>
        </div>



    <div class="footer">
        <span class="left">2025 &copy; COFFEE SHOP ORDER</span>
        <span class="right">Laporan Dibuat: <?= date('d M Y, H:i') ?> WIB</span>
    </div>
</body>
</html>
