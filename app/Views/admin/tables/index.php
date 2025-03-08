<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h3>Daftar Meja</h3>
                    <a href="<?= base_url('tables/create') ?>" class="btn btn-primary btn-sm mb-3">
                        <i class="bi bi-plus-circle"></i> Tambah Meja
                    </a>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Meja</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tables as $key => $table) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $table['table_number'] ?></td>
                                        <td><?= ucfirst($table['status']) ?></td>
                                        <td>
                                            <a href="<?= base_url('tables/edit/' . $table['id']) ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button onclick="deleteTable(<?= $table['id'] ?>)" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteTable(id) {
        Swal.fire({
            title: "Apakah Anda yakin ingin menghapus meja ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('tables/delete/') ?>" + id;
            }
        });
    }
</script>

<?= $this->endSection() ?>
