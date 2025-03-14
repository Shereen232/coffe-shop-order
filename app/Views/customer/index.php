<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>   
  <div id="cart-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 start-50 translate-middle-x mt-3 shadow-lg small-toast" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 999;">
    <div class="d-flex">
      <div class="toast-body d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" style="width: 20px; height: 20px;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        Produk berhasil ditambahkan ke keranjang!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>

    <section class="py-3" style="background-image: url('<?=base_url() ?>FoodMart/images/background-pattern.jpg');background-repeat: no-repeat;background-size: cover;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <div class="banner-ad large bg-info ">

              <div class="swiper main-swiper">
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories my-3">100% natural</div>
                        <h3 class="display-4">Fresh Smoothie & Summer Juice</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                        <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Shop Now</a>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="<?=base_url() ?>FoodMart/images/product-thumb-1.png" class="img-fluid">
                      </div>
                    </div>
                  </div>
                  
                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories mb-3 pb-3">100% natural</div>
                        <h3 class="banner-title">Fresh Smoothie & Summer Juice</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                        <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop Collection</a>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="<?=base_url() ?>FoodMart/images/product-thumb-1.png" class="img-fluid">
                      </div>
                    </div>
                  </div>
                  
                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories mb-3 pb-3">100% natural</div>
                        <h3 class="banner-title">Heinz Tomato Ketchup</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                        <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop Collection</a>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="<?=base_url() ?>FoodMart/images/product-thumb-2.png" class="img-fluid">
                      </div>
                    </div>
                  </div>
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

            <div class="section-header d-flex flex-wrap justify-content-between mb-5">
              <h2 class="section-title">Category</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn-link text-decoration-none">View All Categories →</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
                  <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
                </div>
              </div>
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

    <section class="py-5 overflow-hidden">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex flex-wrap justify-content-between my-5">
              <h2 class="section-title">Best selling products</h2>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="products-container row">
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

        function showCartToast() {
            let toastEl = document.getElementById("cart-toast");
            let toast = new bootstrap.Toast(toastEl);

            toast.show();
        }

        function reloadCart()
        {
          const id = 151515;
          let html = '';
          $.ajax({
            url: `<?= base_url() ?>api/${id}/getcart`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
              const carts = response.carts;
              carts.forEach(product => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                          <h6 class="my-0">${product.name}</h6>
                          <small class="text-body-secondary">${product.description}</small>
                      </div>
                      <div class="d-flex align-items-center">
                          <span class="text-body-secondary me-3">${formatRupiah(product.subtotal)}</span>
                          <button class="btn btn-danger btn-sm delete-item" data-index="${product.items_id}">
                              ❌
                          </button>
                      </div>
                  </li>`;
                });

                html += `<li class="list-group-item d-flex justify-content-between">
                    <span>Total (IDR)</span>
                    <strong>${formatRupiah(carts[0].total)}</strong>
                </li>`;
              $('#cart-count').html(carts.length);
              $('#my-cart #countcart').html(carts.length);
              $('#my-cart ul').html(html);
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan saat mengambil data:', error);
            }
          });
        }reloadCart();

        function getProducts()
        {
          $.ajax({
              url: '<?= base_url() ?>api/products',
              method: 'GET',
              dataType: 'json',
              success: function (data) {
                  const contentParent = $('.products-container');
                  
                  let html = "";
  
                  data.products.forEach(product => {
                      html += `
                      <div class="col-6 col-md-3 col-lg-2">
                        <div class="product-item">
                          <span class="badge bg-success position-absolute m-3">-15%</span>
                          <figure style="overflow:hidden;">
                              <img src="<?=base_url('uploads/products/')?>${product.image}" class="tab-image">
                          </figure>
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
  
                  contentParent.html(html);
              },
              error: function (xhr, status, error) {
                  console.error('Terjadi kesalahan saat mengambil data:', error);
              }
          });
        }getProducts();

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
                          <a href="<?=base_url('?category=')?>${category.nama_category}" class="nav-link category-item swiper-slide">
                            <img src="<?=base_url() ?>${category.image}" alt="${category.nama_category}">
                            <h3 class="category-title">${category.nama_category}</h3>
                          </a>
                      </div></div>`;
                    });
  
                  contentParent.html(html);
              },
              error: function (xhr, status, error) {
                  console.error('Terjadi kesalahan saat mengambil data:', error);
              }
          });
        }getCategories();

        $(document).on('click', '#btn-addchart', function(e) {
          const id = $(this).data('id');
          const qty = $(this).data('qty');
          $.ajax({
            url: `<?= base_url() ?>api/${id}/addcart`,
            method: 'POST',
            dataType: 'json',
            data:{qty},
            success: function (response) {
              showCartToast();
              reloadCart();
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan saat mengambil data:', error);
            }
          });
        });

        $(document).on('click', '#my-cart ul .delete-item', function(e) {
          const id = $(this).data('index');
          $.ajax({
            url: `<?= base_url() ?>api/${id}/deleteitem`,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
              reloadCart();
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan saat mengambil data:', error);
            }
          });
        });
      });
    </script>
<?= $this->endSection() ?>
  