
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paras Selatan</title>

    <link rel="shortcut icon" href="<?= base_url('static/images/logo/favicon.jpg') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url() ?>mazer/assets/compiled/css/app.css">
    <link rel="stylesheet" href="<?= base_url() ?>mazer/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= base_url() ?>mazer/assets/compiled/css/auth.css">
</head>

<body>
    <script src="<?= base_url() ?>mazer/assets/static/js/initTheme.js"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="<?= base_url('/') ?>"><img src="<?= base_url('static/images/logo/favicon.jpg') ?>" class="rounded-circle" alt="Paras Selatan"></a>
                    </div>
                    <h1 class="auth-title">Masuk.</h1>
                    <p class="auth-subtitle mb-5">Mohon masuk terlebih dahulu.</p>

                    <form action="<?= base_url('admin/auth/login') ?>" method="POST">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Masuk</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>
    </div>
</body>

</html>