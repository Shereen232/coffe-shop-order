<?= $this->extend('Customer/template/index.php') ?>
<?= $this->section('app') ?>   
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

            <div class="category-carousel swiper">
              <div class="swiper-wrapper">
                <a href="<?=base_url() ?>/food/category?name=fruit" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                <a href="<?=base_url() ?>/food/category?name=breads" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-bread-baguette.png" alt="Category Thumbnail">
                  <h3 class="category-title">Breads & Sweets</h3>
                </a>
                <a href="<?=base_url() ?>/food/category" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-soft-drinks-bottle.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                <a href="<?=base_url() ?>/food/category" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-wine-glass-bottle.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                <a href="<?=base_url() ?>/food/category" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-animal-products-drumsticks.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                <a href="<?=base_url() ?>/food/category" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-bread-herb-flour.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                <a href="<?=base_url() ?>/food/category" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                <a href="<?=base_url() ?>/food/category" class="nav-link category-item swiper-slide">
                  <img src="<?=base_url() ?>FoodMart/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                  <h3 class="category-title">Fruits & Veges</h3>
                </a>
                
              </div>
            </div>

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
                        <a href="#" class="btn-wishlist">
                          <svg width="24" height="24"><use xlink:href="#heart"></use></svg>
                        </a>
                        <figure>
                          <a href="<?=base_url()?>/food/category" title="${product.name}">
                            <img src="<?=base_url()?>/FoodMart/images/${product.image}" class="tab-image">
                          </a>
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
                          <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon></a>
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
      });
    </script>
<?= $this->endSection() ?>
  