<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>   
<div class="container py-5">
  <div class="mb-4">
    <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
      <i class="bi bi-arrow-left me-2"></i> Home
    </a>
  </div>

  <div class="row g-4">
    <!-- Gambar Produk -->
    <div class="col-md-5 text-center position-relative">
      <span class="badge bg-success position-absolute top-0 start-0 m-3 rounded-pill">-15%</span>
      <img src="<?=base_url('uploads/products/')?><?= $product->image ?>" alt="<?= $product->name ?>" class="img-fluid rounded-4 shadow-sm">
    </div>

    <!-- Detail Produk -->
    <div class="col-md-7">
      <h3 class="fw-bold"><?= $product->name ?></h3>
      <div class="d-flex align-items-center mb-2">
        <span class="me-2 text-muted">1 UNIT</span>
        <i class="bi bi-star-fill text-warning"></i>
        <strong class="ms-1">4.5</strong>
      </div>
      <h4 class="text-success fw-bold mb-3">Rp <?= number_format($product->price, 0, '.', '.') ?></h4>

      <p class="text-muted">
        <?= $product->description ?>
      </p>

      <div class="d-flex align-items-center mb-3">
        <span class="me-2">Qty:</span>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">−</button>
        <span class="mx-2">1</span>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">+</button>
      </div>

      <div class="d-grid gap-2 d-md-block">
        <button id="btn-addchart" data-id="<?= $product->id ?>" data-qty="1" class="btn btn-primary rounded-pill px-4 me-2">
          <i class="bi bi-cart"></i> Add to Cart
        </button>
        <!-- <button class="btn btn-outline-secondary rounded-pill px-4">
          <i class="bi bi-heart"></i> Wishlist
        </button> -->
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
  