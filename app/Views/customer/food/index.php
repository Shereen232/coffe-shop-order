<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>   
<div class="container py-5">

  <!-- Tombol Back -->
  <div class="mb-4">
    <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
      <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
    </a>
  </div>

  <!-- Detail Produk -->
  <div class="row g-4 align-items-start">
    
    <!-- Gambar Produk -->
    <div class="col-lg-5 text-center position-relative">
      <?php if ($product->price_discount > 0): ?>
        <span class="badge bg-success position-absolute top-0 start-0 m-3 rounded-pill">
          -<?= $product->price_discount ?>%
        </span>
      <?php endif; ?>
      <img src="<?= base_url('uploads/products/') . $product->image ?>" alt="<?= $product->name ?>" class="img-fluid rounded-4 shadow-sm">
    </div>

    <!-- Info Produk -->
    <div class="col-lg-7">
      <h3 class="fw-bold"><?= $product->name ?></h3>
      <div class="d-flex align-items-center mb-2">
        <span class="me-2 text-muted">1 UNIT</span>
        <i class="bi bi-star-fill text-warning"></i>
        <strong class="ms-1">4.5</strong>
      </div>

      <h4 class="text-success fw-bold mb-3">Rp <?= number_format($product->price, 0, ',', '.') ?></h4>

      <p class="text-muted"><?= $product->description ?></p>

      <!-- Qty -->
      <div class="d-flex align-items-center mb-3">
        <span class="me-2">Qty:</span>
        <span class="input-group-btn">
          <button type="button" id="btn-minus" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-id="<?= $product->price?>">
            <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
          </button>
        </span>
        <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="<?= $product->stock ?>">
        <span class="input-group-btn">
          <button type="button" id="btn-plus" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-id="<?= $product->price?>">
            <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
          </button>
        </span>
      </div>

      <!-- Tombol Tambah -->
      <div class="d-grid gap-2 d-md-block">
        <button id="btn-addchart" data-id="<?= $product->id ?>" data-qty="1" class="btn btn-primary rounded-pill px-4 me-2">
          <i class="bi bi-cart"></i> Tambah ke Keranjang
        </button>
        <!-- Wishlist optional -->
        <!-- <button class="btn btn-outline-danger rounded-pill px-4">
          <i class="bi bi-heart"></i> Wishlist
        </button> -->
      </div>
    </div>
  </div>

  <!-- Ulasan Produk -->
  <div class="mt-5">
    <!-- <h4 class="mb-4">Ulasan Produk</h4> -->

    <!-- Dummy review loop -->
    <!-- <div class="list-group">
      <?php if ($reviews == null) echo '<div>Belum ada ulasan untuk produk ini.</div>'; ?>
      <?php foreach ($reviews as $key => $review) : ?>
      <?php $timestamp = strtotime($review->created_at); ?>
      <div class="list-group-item">
        <div class="d-flex justify-content-between">
          <h6 class="mb-1"><?= $review->name ?></h6>
          <?php
          $now = new DateTime();
          $created_at = (new DateTime())->setTimestamp($timestamp);
          $interval = $now->diff($created_at);
          $parts = [];
          if ($interval->y > 0) {
              $parts[] = $interval->y . ' tahun';
          }
          if ($interval->m > 0) {
              $parts[] = $interval->m . ' bulan';
          }
          if ($interval->d > 0) {
              $parts[] = $interval->d . ' hari';
          }
          if ($interval->h > 0) {
              $parts[] = $interval->h . ' jam';
          }
          if ($interval->i > 0) {
              $parts[] = $interval->i . ' menit';
          }
          ?>
          <small class="text-muted"><?= !empty($parts) ? $parts[0] . ' yang lalu' : 'baru saja'; ?></small>
        </div>
        <p class="mb-1"><?= $review->comment ?></p>
        <div style="color: orange;">
        <?= str_repeat('★', $review->rating)?><?=str_repeat('☆', 5 - $review->rating) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div> -->

    <!-- Tombol Tambah Ulasan -->
    <!-- <div class="mt-4">
      <a href="#form-review" class="btn btn-outline-primary rounded-pill">Tulis Ulasan</a>
    </div> -->
  </div>
</div>

<script>
    const qtyInput = document.getElementById("quantity");
    const minusBtn = document.getElementById('btn-minus');
    const plusBtn = document.getElementById('btn-plus');
    const maxStock = "<?= $product->stock ?>";

    minusBtn.addEventListener('click', () => {
      let val = parseInt(qtyInput.value) || 1;
      qtyInput.value = val > 1 ? val - 1 : 1;
      setValue(qtyInput.value)
    });

    plusBtn.addEventListener('click', () => {
      let val = parseInt(qtyInput.value) || 1;
      qtyInput.value = val < maxStock ? val + 1 : maxStock;
      setValue(qtyInput.value)
    });

    function setValue(val)
    {
      const qtyInput = document.querySelector(`#btn-addchart`);
      qtyInput.setAttribute('data-qty', val);
    }
</script>
<?= $this->endSection() ?>
  