<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>
<div class="container py-5">
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
        </a>
    </div>

    <h3 class="fw-bold mb-4">Detail Pesanan</h3>

    <div class="row g-4">
        <!-- Info Pesanan -->
        <div class="col-lg-7">
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Informasi Pemesan</h5>
                    <ul class="list-unstyled">
                        <li><strong>Trx ID: </strong><?= $order->payment->transaction_id ?></li>
                        <li><strong>Nomor Meja: </strong><?= $order->table ?></li>
                        <li><strong>Metode Pembayaran:</strong> <?= $order->payment->payment_method ?></li>
                        <li><strong>Status:</strong> <?php if ($order->payment->payment_status == 'pending') {
                            echo '<span class="badge bg-warning text-dark">Menunggu Pembayaran</span>';
                        }else if ($order->payment->payment_status == 'paid') {
                            echo '<span class="badge bg-success">Pembayaran Diterima</span>';
                        }else if ($order->payment->payment_status == 'failed') {
                            echo '<span class="badge bg-danger">Pesanan Dibatalkan</span>';
                        }
                         ?></li>
                        <li><strong>Tanggal Pemesanan:</strong> <?= date('d M Y, H:i', strtotime($order->created_at)) ?></li>
                    </ul>
                </div>
            </div>
            <?php if ($order->status == 'pending'): ?>
                <div class="alert alert-warning" role="alert">
                    Silakan lakukan pembayaran di kasir untuk menyelesaikan pesanan Anda.
                </div>
            <?php elseif ($order->status == 'processing'): ?>
                <div class="alert alert-success" role="alert">
                    Pembayaran Anda telah diterima. Terima kasih telah berbelanja!
                </div>
                <a href="<?= base_url('order/struk/' . $order->id) ?>" class="btn btn-outline-success rounded-pill px-4 shadow-sm mb-4" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-2"></i> Download Struk (PDF)
                </a>
            <?php elseif ($order->status == 'completed'): ?>
                <div class="alert alert-danger" role="alert">
                    Pesanan Anda telah dibatalkan. Silakan hubungi kami untuk informasi lebih lanjut.
                </div>
            <?php endif; ?>
        </div>

        <!-- Ringkasan Produk -->
        <div class="col-lg-5">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Ringkasan Produk</h5>
                    <?php foreach ($order_items as $item): ?>
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?= base_url('uploads/products/' . $item->image) ?>" class="rounded me-3" width="60" height="60" alt="<?= esc($item->name) ?>">
                            <div>
                                <h6 class="mb-1"><?= esc($item->name) ?></h6>
                                <small class="text-muted"><?= $item->quantity ?> x Rp <?= number_format($item->price, 0, ',', '.') ?></small>
                            </div>
                            <div class="ms-auto fw-semibold">
                                Rp <?= number_format($item->quantity * $item->price, 0, ',', '.') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr>

                    <!-- Total -->
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong class="text-success">Rp <?= number_format($order->total_price, 0, ',', '.') ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
