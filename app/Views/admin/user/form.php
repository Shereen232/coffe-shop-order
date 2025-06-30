<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h3><?= isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h3>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="<?= isset($user) ? base_url('admin/users/update/' . $user->id) : base_url('admin/users/store') ?>" method="post">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required value="<?= isset($user) ? esc($user->name) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required value="<?= isset($user) ? esc($user->email) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Telepon</label>
                        <input type="text" name="phone" class="form-control" value="<?= isset($user) ? esc($user->phone) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control"><?= isset($user) ? esc($user->address) : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= isset($user) && $user->role == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="kitchen" <?= isset($user) && $user->role == 'kitchen' ? 'selected' : '' ?>>Kitchen</option>
                            <option value="owner" <?= isset($user) && $user->role == 'owner' ? 'selected' : '' ?>>Owner</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Password <?= isset($user) ? '(Biarkan kosong jika tidak ingin mengubah)' : '' ?></label>
                        <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Kembali</a>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
