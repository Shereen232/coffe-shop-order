<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <h3>Data Kategori Produk</h3>
                            <p class="text-subtitle text-muted">Halaman untuk manajemen kategori produk seperti melihat, mengubah, dan menghapus.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url() ?>admin/category-product/create">
                                <button type="button" class="btn btn-primary btn-m mb-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Kategori 
                                </button>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTable" class="table">
                                <thead>
                                    <tr>
                                        <th> No </th>
                                        <th> Nama Kategori </th>
                                        <th> Gambar </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $key => $value) : ?>
                                        <tr>
                                            <td> <?= $key + 1 ?> </td>
                                            <td> <?= $value['nama_category'] ?> </td>
                                             <td>
                                                <?php if (!empty($value['image'])) : ?>
                                                    <img src="<?= base_url('uploads/category/' . $value['image']) ?>" alt="Kategori" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                <?php else : ?>
                                                    <span class="text-muted">Tidak ada gambar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td> 
                                                <a href="<?= base_url() ?>admin/category-product/edit/<?= $value['id'] ?>" type="button" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteCategory(<?= $value['id'] ?>)"> 
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
    function deleteCategory(id)
    {
        Swal.fire({
            title: "Apakah anda yakin ingin menghapus?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/category-product/delete/"+id,
                    success: function(result){
                        Swal.fire({
                            allowOutsideClick: false,
                            title: "Deleted!",
                            text: "Your category has been deleted.",
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
