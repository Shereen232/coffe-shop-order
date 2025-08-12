<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>
<style>
  .active-category {
    background-color: #85A83A !important; /* Bootstrap green */
    color: white !important;
    border-radius: 10px;
    transition: 0.2s;
  }
  .categories-container .active-category .category-title{
    color: white !important;
  }
  /* Gambar Produk */
  .product-item figure {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 12px;
  }

  .product-item img.tab-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.3s ease;
  }

  .product-item img.tab-image:hover {
    transform: scale(1.05);
  }

  /* Gambar Kategori */
  .category-image {
    width: 100px;
    height: 100px;
    object-fit: contain;
    object-position: center;
    margin-bottom: 10px;
    padding: 8px;
    
  }

  /* Perbaikan Tombol Kategori */
  .category-item {
    width: 100%;
    background: none;
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .category-title {
    font-size: 0.9rem;
    color: #333;
    text-align: center;
  }

</style>

    <section class="py-3" style="background-image: url('<?=base_url() ?>FoodMart/images/background-pattern.jpg');background-repeat: no-repeat;background-size: cover;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <div class="banner-ad large bg-info ">

              <div class="swiper main-swiper">
                <div id="product-banners" class="swiper-wrapper">
                  
                </div>
                
                <div class="swiper-pagination"></div>

              </div>
            </div>
            <!-- / Banner Blocks -->
              
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 overflow-hidden">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header text-center mb-5">
              <h2 class="section-title">Products</h2>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
          <div class="categories-container row"></div>
          </div>
        </div>
      </div>
    </section>

    <section class="pb-5 overflow-hidden">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <div id="products-section" class="products-container row">
            </div>
            <!-- / products-carousel -->

          </div>
        </div>
      </div>
    </section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

      function formatRupiah(angka) {
          return new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0, // Menghilangkan angka di belakang koma
              maximumFractionDigits: 0
          }).format(angka);
      }

      function reloadProducts(category = null) {
        $.ajax({
          url: '<?= base_url() ?>api/products',
          method: 'GET',
          dataType: 'json',
          data: { category: category },
          success: function (data) {
            const contentParent = $('.products-container');
            let html = "";

            if (data.products && data.products.length > 0) {
              data.products.forEach(product => {
                const qtyInputId = `quantity-${product.id}`;
                const minusBtnId = `minus-${product.id}`;
                const plusBtnId = `plus-${product.id}`;

                html += `
                <div class="col-6 col-md-4 col-lg-3">
                  <div class="product-item">
                    
                    <a href="<?= base_url() ?>product/${product.id}">
                      <figure style="overflow:hidden;">
                        <img src="<?= base_url('uploads/products/') ?>${product.image}" class="tab-image">
                      </figure>
                    </a>
                    <h3>${product.name}</h3>
                    <span class="qty"></span>
                    <span class="price">${formatRupiah(product.price)}</span>
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="input-group product-qty">
                        <span class="input-group-btn">
                          <button type="button" id="${minusBtnId}" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-id="${product.id}">
                            <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                          </button>
                        </span>
                        <input type="text" id="${qtyInputId}" name="quantity" class="form-control input-number" value="1" min="1" max="${product.stock}">
                        <span class="input-group-btn">
                          <button type="button" id="${plusBtnId}" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-id="${product.id}">
                            <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                          </button>
                        </span>
                      </div>
                      <button id="btn-addchart" data-id="${product.id}" data-qty="1" class="nav-link"> Tambahkan Ke Keranjang <iconify-icon icon="uil:shopping-cart"></iconify-icon></button>
                    </div>
                  </div>
                </div>
                `;
              });
            } else {
              html = '<p class="text-center">Produk tidak ditemukan.</p>';
            }

            contentParent.html(html);

            // Setelah elemen dimuat, pasang event listener untuk setiap produk
            if (data.products && data.products.length > 0) {
              data.products.forEach(product => {
                const qtyInput = document.getElementById(`quantity-${product.id}`);
                const minusBtn = document.getElementById(`minus-${product.id}`);
                const plusBtn = document.getElementById(`plus-${product.id}`);
                const maxStock = parseInt(product.stock);

                minusBtn.addEventListener('click', () => {
                  let val = parseInt(qtyInput.value) || 1;
                  qtyInput.value = val > 1 ? val - 1 : 1;
                  setValue(product.id, qtyInput.value)
                });

                plusBtn.addEventListener('click', () => {
                  let val = parseInt(qtyInput.value) || 1;
                  qtyInput.value = val < maxStock ? val + 1 : maxStock;
                  setValue(product.id, qtyInput.value)
                });

                qtyInput.addEventListener('input', () => {
                  let val = parseInt(qtyInput.value);
                  if (isNaN(val) || val < 1) {
                    qtyInput.value = 1;
                  } else if (val > maxStock) {
                    qtyInput.value = maxStock;
                  }
                });                
              });
            }
          },
          error: function (xhr, status, error) {
            console.error('Terjadi kesalahan saat mengambil data:', error);
          }
        });
      }

      function setValue($id, val)
      {
        const qtyInput = document.querySelector(`#btn-addchart[data-id="${$id}"]`);
        qtyInput.setAttribute('data-qty', val);
      }

      function getBanners()
      {
        $.ajax({
            url: '<?= base_url() ?>api/banners',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                const contentParent = $('#product-banners');
                
                let html = "";

                data.banners.forEach(banner => {
                    html += `
                    <div class="swiper-slide position-relative">
                      <div class="row banner-content p-5">
                        <div class="content-wrapper col-md-7">
                          <div class="categories my-3 fw-bold">Best Seller</div>
                          <h4 class="display-5">${banner.name}</h4>
                          <p>${banner.description}</p>
                          <div class="position-absolute m-3 badge bg-warning text-dark rounded-pill shadow" style="top: 50%; left: 55%;">
                            <h1 style="font-size:3rem; padding:1rem 2rem;">Only 30k</h1>
                          </div>
                          <a href="${baseUrl}product/${banner.product_id}" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Pesan</a>
                        </div>
                        <div class="img-wrapper col-md-5">
                          <img src="${baseUrl}uploads/category/${banner.image}" class="img-fluid">
                        </div>
                      </div>
                    </div>`;
                });


                contentParent.html(html);
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan saat mengambil data:', error);
            }
        });
      }getBanners();

      function getCategories()
      {
        $.ajax({
            url: '<?= base_url() ?>api/categories',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                const contentParent = $('.categories-container');
                
                let html = "";

                data.categories.forEach(category => {
                    html += `
                    <div class="col-4 col-md-3 col-lg-2">
                      <div class="product-item d-flex flex-column align-items-center justify-content-center text-center">
                        <button data-id="${category.id}" class="nav-link category-item swiper-slide pt-2 pb-2">
                          <img src="<?=base_url() ?>/uploads/category/${category.image}" alt="${category.nama_category}" class="category-image">
                          <h3 class="category-title">${category.nama_category}</h3>
                        </button>
                    </div></div>`;
                  });

                contentParent.html(html);
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan saat mengambil data:', error);
            }
        });
      }getCategories();

      $(document).on('click', '.category-item', function(e) {
        e.preventDefault();
          // Hapus kelas active dari semua kategori
        $('.category-item').removeClass('active-category');

        // Tambahkan kelas active pada yang diklik
        $(this).addClass('active-category');

        const id = $(this).data('id');
      
        if (id) {
          reloadProducts(id);
        } else {
          reloadProducts();
        }

        const productSection = document.getElementById('products-section');
        if (productSection) {
            productSection.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
      });

      $(document).ready(function() {
        reloadProducts();
      });
  });
</script>
<?= $this->endSection() ?>
  