<!DOCTYPE html>
<html lang="en">
  <head>
    <title>FoodMart - Free eCommerce Grocery Store HTML Website Template</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=base_url() ?>Foodmart/css/vendor.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url() ?>Foodmart/style.css">
    <link rel="shortcut icon" href="<?= base_url('static/images/logo/favicon.jpg') ?>" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
    a {
      text-decoration: none !important;
    }
    </style>
  </head>
  <body>
    <!-- Modal Snap bisa kamu tempatkan jika pakai embed -->
    <div class="modal fade" id="modalBooking" tabindex="-1" aria-labelledby="modalBookingLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content" style="height: 100vh;">
          <div id="snap-container" style="width: 100%; height:100%"></div>
        </div>
      </div>
    </div>
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
    <?= $this->include('customer/partials/header') ?>       
        <?= $this->renderSection('app') ?>
    <?= $this->include('customer/partials/footer') ?>  

    <script src="<?=base_url() ?>Foodmart/js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="<?=base_url() ?>Foodmart/js/plugins.js"></script>
    <script src="<?=base_url() ?>Foodmart/js/script.js"></script>
    <script>
      function showCartToast() {
          let toastEl = document.getElementById("cart-toast");
          let toast = new bootstrap.Toast(toastEl);

          toast.show();
      }

      function formatRupiah(angka) {
          return new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0, // Menghilangkan angka di belakang koma
              maximumFractionDigits: 0
          }).format(angka);
      }

      function reloadCart()
      {
        const id = <?= session('session')['table_id'] ?? '0' ?>;
        let html = '';
        $.ajax({
          url: `<?= base_url() ?>api/${id}/getcart`,
          method: 'GET',
          dataType: 'json',
          success: function (response) {
            const carts = response.carts;
            if (carts && carts.length > 0) {
              carts.forEach(product => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                          <h6 class="my-0">${product.name} x${product.qty}</h6>
                      </div>
                      <div class="d-flex align-items-center">
                          <span class="text-body-secondary me-3">${formatRupiah(product.subtotal)}</span>
                          <button class="btn btn-danger btn-sm delete-item" data-index="${product.items_id ?? product.product_id}">
                              ❌
                          </button>
                      </div>
                  </li>`;
              });
  
              html += `<li class="list-group-item d-flex justify-content-between">
                  <span>Total (IDR)</span>
                  <strong>${formatRupiah(carts[0].total)}</strong>
              </li>`;
              $('#cart-count').removeClass('d-none');
              $('#cart-count').html(carts.length);
            }else html += 'keranjang masih kosong';
            
            $('#my-cart #countcart').html(carts.length);
            $('#my-cart ul').html(html);
          },
          error: function (xhr, status, error) {
              console.error('Terjadi kesalahan saat mengambil data:', error);
          }
        });
      }reloadCart();

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

        // $(document).on('click', '#btn-checkout', function(e) {
        //   const id = 151515;
        //   $.ajax({
        //     url: `<?= base_url() ?>api/${id}/checkout`,
        //     method: 'POST',
        //     dataType: 'json',
        //     success: function (response) {
        //       alert(response.message);
        //       window.location.href = '<?= base_url() ?>checkout';
        //     },
        //     error: function (xhr, status, error) {
        //         console.error('Terjadi kesalahan saat mengambil data:', error);
        //     }
        //   });
        // });

    </script>
  </body>
</html>