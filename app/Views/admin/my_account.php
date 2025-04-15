<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <h3>Akun Saya</h3>
                            <p class="text-subtitle text-muted">Halaman untuk melihat dan mengelola informasi akun admin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="200">Nama Lengkap</th>
                                        <td><?= esc($user['nama_lengkap']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= esc($user['email']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Username</th>
                                        <td><?= esc($user['username']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td><?= esc($user['role']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Bergabung Sejak</th>
                                        <td><?= date('d F Y', strtotime($user['created_at'])) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="#" class="btn btn-primary btn-sm me-2">
                                <i class="bi bi-pencil-square"></i> Edit Profil
                            </a>
                            <a href="#" class="btn btn-secondary btn-sm">
                                <i class="bi bi-key-fill"></i> Ubah Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
