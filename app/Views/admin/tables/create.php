<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Meja</h4>
                    <form action="<?= base_url('admin/tables/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="table_number">Nomor Meja</label>
                            <input type="text" class="form-control" name="table_number" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="available">Tersedia</option>
                                <option value="occupied">Terpakai</option>
                                <option value="reserved">Dipesan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
