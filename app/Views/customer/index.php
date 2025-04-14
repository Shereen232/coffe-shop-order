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

        function reloadProducts(category = null)
        {
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
                      html += `
                      <div class="col-6 col-md-3 col-lg-2">
                        <div class="product-item">
                          <span class="badge bg-success position-absolute m-3">${product.price_discount <= 0 ? '' : '-'+product.price_discount+'%'}</span>
                          <a href="<?= base_url() ?>product/${product.id}">
                          <figure style="overflow:hidden;">
                              <img src="<?=base_url('uploads/products/')?>${product.image}" class="tab-image">
                          </figure>
                          </a>
                          <h3>${product.name}</h3>
                          <span class="qty">1 Unit</span>
                          <span class="rating">
                            <svg width="24" height="24" class="text-primary">
                              <use xlink:href="#star-solid"></use>
                            </svg> 4.5
                          </span>
                          <span class="price">${formatRupiah(product.price)}</span>
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="input-group product-qty">
                              <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                  <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                                </button>
                              </span>
                              <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                              <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                  <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                                </button>
                              </span>
                            </div>
                            <button id="btn-addchart" data-id="${product.id}" data-qty="1" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon></a>
                          </div>
                        </div>
                      </div>`;
                    });
                  } else {
                      // Tampilkan pesan kalau produk kosong
                      html = '<p class="text-center">Produk tidak ditemukan.</p>';
                  }
  
                  contentParent.html(html);
              },
              error: function (xhr, status, error) {
                  console.error('Terjadi kesalahan saat mengambil data:', error);
              }
          });
        }reloadProducts();

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
                      <div class="swiper-slide">
                        <div class="row banner-content p-5">
                          <div class="content-wrapper col-md-7">
                            <div class="categories my-3">100% natural</div>
                            <h3 class="display-4">${banner.name}</h3>
                            <p>${banner.description}</p>
                            <a href="<?=base_url('product/')?>${banner.product_id}" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Shop Now</a>
                          </div>
                          <div class="img-wrapper col-md-5">
                            <img src="<?=base_url('uploads/products/')?>${banner.image}" class="img-fluid">
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
                            <img src="<?=base_url() ?>${category.image}" alt="${category.nama_category}">
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

      });
    </script>
<?= $this->endSection() ?>
  