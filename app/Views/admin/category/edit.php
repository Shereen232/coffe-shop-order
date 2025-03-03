<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Edit Kategori</h4>
                    <p class="card-description">Harap mengisikan data kategori yang ingin diubah</p>
                    <form method="post" class="forms-sample">
                        <?= csrf_field() ?>

                        <!-- Nama Kategori -->
                        <div class="form-group">
                            <label for="nama_category">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_category" placeholder="Nama Kategori" value="<?= old('nama_category', $category['nama_category']) ?>" required>
                            <small class="text-danger"><?= !empty(session()->getFlashdata('validation')['nama_category']) ? session()->getFlashdata('validation')['nama_category'] : '' ?></small>
                        </div>

                        <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
