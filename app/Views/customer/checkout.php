<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>
    <div class="container py-5">
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
        </a>
    </div>

    <h3 class="fw-bold mb-4">Checkout</h3>

    <div class="row g-4">
        <!-- Kolom Kiri: Formulir Checkout -->
        <div class="col-lg-7">
        <!-- Info Pengiriman -->
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-body">
            <h5 class="fw-semibold mb-3">Metode Pembayaran</h5>
            <form action="<?= base_url('checkout') ?>" method="post">
                <!-- <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control rounded-pill" required>
                </div>
                <div class="mb-3">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="telepon" class="form-control rounded-pill" required>
                </div>
                <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control rounded-4" rows="3" required></textarea>
                </div> -->
                <div class="form-check">
                <input class="form-check-input" type="radio" name="metode_pembayaran" id="cash" value="cash" checked>
                <label class="form-check-label" for="cash">Cash</label>
                </div>
                <div class="form-check">
                <input class="form-check-input" type="radio" name="metode_pembayaran" id="online_payment" value="online_payment">
                <label class="form-check-label" for="online_payment">Online Payment</label>
                </div>

                <!-- Tombol Checkout -->
                <div class="mt-4">
                <button type="submit" class="btn btn-success rounded-pill px-5">
                    <i class="bi bi-bag-check-fill me-2"></i> Konfirmasi & Checkout
                </button>
                </div>
            </form>
            </div>
        </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Pesanan -->
        <div class="col-lg-5">
        <div class="card shadow-sm rounded-4">
            <div class="card-body">
            <h5 class="fw-semibold mb-3">Ringkasan Pesanan</h5>

            <!-- Contoh produk di keranjang -->
            <?php foreach ($orders as $item): ?>
                <div class="d-flex align-items-center mb-3">
                <img src="<?= base_url('uploads/products/' . $item->image) ?>" class="rounded me-3" width="60" height="60" alt="<?= $item->name ?>">
                <div>
                    <h6 class="mb-1"><?= $item->name ?></h6>
                    <small class="text-muted"><?= $item->qty ?> x Rp <?= number_format($item->subtotal, 0, ',', '.') ?></small>
                </div>
                <div class="ms-auto fw-semibold">
                    Rp <?= number_format($item->subtotal * $item->qty, 0, ',', '.') ?>
                </div>
                </div>
            <?php endforeach; ?>

            <hr>

            <!-- Total -->
            <div class="d-flex justify-content-between">
                <strong>Total</strong>
                <strong class="text-success">Rp <?= number_format($total, 0, ',', '.') ?></strong>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
<?= $this->endSection() ?>