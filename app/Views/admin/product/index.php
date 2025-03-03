<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <h3>Data Produk</h3>
                            <p class="text-subtitle text-muted">Halaman untuk manajemen produk seperti melihat, mengubah, dan menghapus produk.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url() ?>admin/product/create">
                                <button type="button" class="btn btn-primary btn-sm mb-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk 
                                </button>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTable" class="table">
                                <thead>
                                    <tr>
                                        <th> No </th>
                                        <th> Nama Produk </th>
                                        <th> Kategori </th>
                                        <th> Stok </th>
                                        <th> Harga </th>
                                        <th> Gambar </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $key => $value) : ?>
                                        <tr>
                                            <td> <?= $key + 1 ?> </td>
                                            <td> <?= $value['name'] ?> </td>
                                            <td> <?= $value['category_id'] ?> </td>
                                            <td> <?= $value['stock'] ?> </td>
                                            <td> <?= number_format($value['price'], 0, ',', '.') ?> </td>
                                            <td> <img src="<?= base_url('uploads/'.$value['image']) ?>" alt="<?= $value['name'] ?>" width="50" height="50"> </td>
                                            <td> 
                                                <a href="<?= base_url() ?>product/edit/<?= $value['id'] ?>" type="button" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteProduct(<?= $value['id'] ?>)"> 
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
</div>

<script>
    function deleteProduct(id)
    {
        Swal.fire({
            title: "Apakah anda yakin ingin menghapus produk ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>product/delete/"+id,
                    success: function(result){
                        Swal.fire({
                            allowOutsideClick: false,
                            title: "Deleted!",
                            text: "Produk telah dihapus.",
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) location.reload();
                        });
                    },
                    error:function(err){
                        Swal.fire({
                            allowOutsideClick: false,
                            title: "Error!",
                            text: err.responseJSON.message,
                            icon: "warning"
                        });
                    }
                })
            }
        });
    }
</script>

<?= $this->endSection() ?>
