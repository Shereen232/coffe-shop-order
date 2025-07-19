<?= $this->extend('admin/template/index.php') ?>
<?= $this->section('app') ?>
<div class="page-heading">
    <h3>Profile Statistics</h3>
</div> 
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Profile Views</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_views ?></h6>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card"> 
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Produk</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_products ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Pesanan</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_orders ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Pembayaran</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_payments ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-profile-visit"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pesanan Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>ID transaksi</th>
                                            <th>ID Meja</th>
                                            <th>Nama Pemesan</th>
                                            <th>Jumlah</th>
                                            <th>Waktu Pemesanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($latest_orders) > 0): ?>
                                            <?php foreach ($latest_orders as $order): ?>
                                                <tr>
                                                    <td class="col-3">
                                                        <p class="font-bold ms-3 mb-0"><?= $order->transaction_id ?></p>
                                                    </td>
                                                    <td class="col-2">
                                                        <p class="font-bold ms-3 mb-0"><?= $order->table_number ?></p>
                                                    </td>
                                                    <td class="col-3">
                                                        <p class="ms-3 mb-0"><?= $order->nama ?></p>
                                                    <td class="col-3">
                                                        <p class="mb-0">Rp. <?= number_format($order->total_price, 0, '.', '.') ?></p>
                                                    </td>
                                                    <td class="col-3">
                                                        <p class="mb-0"><?= \CodeIgniter\I18n\Time::parse($order->created_at)->humanize() ?></p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center"><i>belum ada data pesanan.</i></td>
                                            <tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4>Review Terbaru</h4>
                </div>
                <div class="card-content pb-4">
                    <?php foreach ($reviews as $review): ?>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="<?= base_url('static/images/faces/default.jpg') ?>">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1"><?= $review->name ?></h5>
                            <p class="mb-0"><?= $review->comment ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>
               
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->endSection() ?>