<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="#"><img src="<?= base_url('static/images/logo/favicon.jpg') ?>" style="width: 80px; height:80px" alt="Logo" class="rounded-circle" srcset=""></a>
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                    <!-- Theme toggle icon here -->
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <!-- Dashboard Menu -->
                <li class="sidebar-item">
                    <a href="<?= base_url('admin') ?>" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                 <!-- >Kategori Produk Menu -->
                 <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/category-product">
                        <i class="bi bi-archive"></i>
                        <span class="menu-title">Kategori Produk</span>
                    </a>
                </li>

                <!-- Manajemen Produk Menu -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/products">
                        <i class="bi bi-box"></i>
                        <span class="menu-title">Manajemen Produk</span>
                    </a>
                </li>

                <!-- Manajemen Meja Menu -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/tables">
                        <i class="bi bi-table"></i>
                        <span class="menu-title">Manajemen Meja</span>
                    </a>
                </li>

                <!-- Manajemen Pesanan Menu -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/orders">
                        <i class="bi bi-cart"></i>
                        <span class="menu-title">Manajemen Pesanan</span>
                    </a>
                </li>

                <!-- Manajemen Keuangan Menu -->
                <!-- Manajemen Keuangan – dengan submenu -->
                <li class="sidebar-item">
                    <a href="<?= base_url() ?>admin/keuangan" class="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span class="menu-title">Manajemen Keuangan</span>
                    </a>
                </li>



                <!-- Review Management Menu -->
                <!-- <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/reviews">
                        <i class="bi bi-star"></i>
                        <span class="menu-title">Review Management</span>
                    </a>
                </li> -->

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/users">
                        <i class="bi bi-people"></i>
                        <span class="menu-title">Manajemen Pengguna</span>
                    </a>
                </li>

                <!-- Reports Menu -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/setting/payment">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span class="menu-title">Pengaturan</span>
                    </a>
                </li>

                <!-- Profile Menu -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url() ?>admin/my-account">
                        <i class="bi bi-person-circle"></i>
                        <span class="menu-title">Akun Saya</span>
                    </a>
                </li>

                <!-- Logout Menu -->
                <li class="sidebar-item">
                    <form action="<?= base_url('admin/auth/logout') ?>" method="POST" id="logout">
                        <?= csrf_field() ?>
                        <button class="sidebar-link bg-white border-0" type="submit" style="width: 100%; text-align: left;">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .sidebar-link {
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Efek untuk elemen yang aktif */
    .sidebar-item.active .sidebar-link {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    .sidebar-item.active .submenu {
        display: none;
    }

    /* Efek hover */
    .sidebar-link:hover {
        background-color: #0056b3;
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        const currentPath = window.location.pathname;

        sidebarItems.forEach(function (item) {
            const link = item.querySelector('a');

            // Memeriksa apakah link cocok dengan halaman yang aktif
            if (link) {
                const linkPath = new URL(link.href).pathname;
                if (linkPath === currentPath) {
                    item.classList.add('active');
                    const submenu = item.querySelector('.submenu');
                    if (submenu) {
                        submenu.style.display = 'block';  // Menampilkan submenu jika aktif
                    }
                }
            }

            // Menangani klik pada item dengan submenu
            item.addEventListener('click', function (e) {
                if (item.classList.contains('has-sub')) {
                    e.preventDefault();  // Mencegah navigasi ke halaman jika ada submenu
                    const submenu = item.querySelector('.submenu');
                    if (submenu) {
                        // Toggle submenu dengan efek show/hide
                        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    }
                    item.classList.toggle('active');  // Toggle kelas 'active' untuk submenu
                }
            });
        });
    });
</script>
