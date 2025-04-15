<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paras Selatan</title>

	<link rel="stylesheet" href="<?= base_url() ?>mazer/assets/extensions/choices.js/public/assets/styles/choices.css" />
    
    <link rel="shortcut icon" href="<?= base_url('static/images/logo/favicon.jpg') ?>" type="image/x-icon">
    
    <link rel="stylesheet" href="<?= base_url() ?>mazer/assets/compiled/css/app.css">
    <link rel="stylesheet" href="<?= base_url() ?>mazer/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= base_url() ?>mazer/assets/compiled/css/iconly.css">
	<link rel="stylesheet" href="<?= base_url() ?>mazer/assets/extensions/bootstrap-icons/font/bootstrap-icons.css" />
	<link rel="stylesheet" href="<?= base_url() ?>mazer/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css" />
	<link rel="stylesheet" href="<?= base_url() ?>mazer/assets/extensions/datatables.net/responsive.bootstrap5.css">
	<link rel="stylesheet" href="<?= base_url() ?>mazer/assets/extensions/sweetalert2/sweetalert2.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>mazer/assets/extensions/flatpickr/flatpickr.min.css" />
</head>

<body>
    <script src="<?= base_url() ?>mazer/assets/static/js/initTheme.js"></script>
    <div id="app">
        <?= $this->include('admin/partials/_sidebar') ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
            
            <?= $this->renderSection('app') ?>

            <?= $this->include('admin/partials/_footer') ?>
        </div>
    </div>

    <script src="<?= base_url() ?>mazer/assets/extensions/jquery/jquery.min.js"></script>
	<script src="<?= base_url() ?>mazer/assets/extensions/datatables.net/jquery.dataTables.min.js"></script>
	<script src="<?= base_url() ?>mazer/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
	<script src="<?= base_url() ?>mazer/assets/extensions/datatables.net/dataTables.responsive.js"></script>
	<script src="<?= base_url() ?>mazer/assets/extensions/datatables.net-bs5/js/responsive.bootstrap5.js"></script>
	<script src="<?= base_url() ?>mazer/assets/extensions/flatpickr/flatpickr.min.js"></script>
	<script src="<?= base_url() ?>mazer/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>mazer/assets/static/js/components/dark.js"></script>
    <script src="<?= base_url() ?>mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?= base_url() ?>mazer/assets/compiled/js/app.js"></script> 

    <script src="<?= base_url() ?>mazer/assets/static/js/pages/dashboard.js"></script>
    <script src="<?= base_url() ?>mazer/assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="<?= base_url() ?>mazer/assets/static/js/pages/form-element-select.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    
    <script>
        $('#dataTable').DataTable();

        const succesSessionFlashMsg = '<?= session()->getFlashdata('success_message') ?>';
        const errorSessionFlashMsg = '<?= session()->getFlashdata('error_message') ?>';
        if (succesSessionFlashMsg !== '') {
            Toast.fire({
                icon: 'success',
                title: succesSessionFlashMsg
            })
        }

        if (errorSessionFlashMsg !== '') {
            Toast.fire({
                icon: 'warning',
                title: errorSessionFlashMsg
            })
        }
        
          
    </script>

</body>

</html>