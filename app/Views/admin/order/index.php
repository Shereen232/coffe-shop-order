<?= $this->extend('admin/template/index.php') ?>

<?= $this->section('app') ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <h3><?= $title ?></h3> <p class="text-subtitle text-muted">Halaman untuk manajemen pesanan produk.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table">
                                <thead>
                                    <tr>
                                        <th> ID Pesanan </th>
                                        <th> No Meja </th>
                                        <th> Trx ID </th>
                                        <th> Payment Method </th>
                                        <th> Payment Status </th>
                                        <th> Total Payment </th>
                                        <th> Detail </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $key => $order) : ?>
                                        <?php if ($order->payment) :?>
                                        <tr>
                                            <td> <?= $order->id ?> </td>
                                            <td> <?= $order->table_number ?? 'N/A' ?> </td>
                                            <td> <?= $order->payment->transaction_id ?? 'Belum Dibayar' ?> </td> 
                                            <td> <?= $order->payment->payment_method ?? '-' ?> </td> 
                                            <td>
                                                <?php
                                                    $status = $order->payment->payment_status ?? 'Pending';
                                                    $badgeClass = '';

                                                    switch (strtolower($status)) {
                                                        case 'settlement':
                                                            $badgeClass = 'bg-success';
                                                            break;
                                                        case 'pending':
                                                            $badgeClass = 'bg-warning';
                                                            break;
                                                        case 'failed':
                                                        case 'cancelled':
                                                            $badgeClass = 'bg-danger';
                                                            break;
                                                        default:
                                                            $badgeClass = 'bg-secondary';
                                                            break;
                                                    }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                            </td>
                                            <td> Rp <?= number_format($order->total_price ?? 0, 0, '.', '.') ?> </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetailModal" data-order-id="<?= $order->id ?>" style="color: white;">
                                                    <i class="bi bi-info-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endif;?>
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

<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Detail Pesanan <span id="modalOrderId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                Loading details...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    // Fungsi deleteCategory(id) tetap ada jika mungkin digunakan di bagian lain dari template admin,
    // tetapi tidak ada tombol aksi yang memicunya di halaman ini lagi.
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

    // JavaScript untuk memuat detail pesanan ke modal menggunakan AJAX
    $(document).ready(function() {
        $('#orderDetailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var orderId = button.data('order-id'); // Ekstrak info dari atribut data-order-id
            var modal = $(this);

            modal.find('.modal-title #modalOrderId').text(orderId); // Tampilkan ID di judul modal
            modal.find('.modal-body').html('Loading details...'); // Reset konten modal

            // Lakukan panggilan AJAX untuk mendapatkan detail pesanan
            $.ajax({
                url: "<?= base_url('admin/orders/get_detail/') ?>" + orderId,
                type: "GET",
                dataType: "json", // Harap server mengembalikan JSON
                success: function(response) {
                    if (response.success) {
                        var order = response.data;
                        var detailHtml = `
                            <p><strong>ID Pesanan:</strong> ${order.id}</p>
                            <p><strong>No Meja:</strong> ${order.table_number || 'N/A'}</p>
                            <p><strong>Tanggal Pesanan:</strong> ${order.created_at || 'N/A'}</p>
                            <hr>
                            <h5>Item Pesanan:</h5>
                            <ul>
                        `;
                        if (order.items && order.items.length > 0) {
                            order.items.forEach(function(item) {
                                detailHtml += `
                                    <li>
                                        ${item.product_name} (${item.quantity}x) - Rp ${new Intl.NumberFormat('id-ID').format(item.price)}
                                        <br>
                                        <img src="<?= base_url('uploads/products/') ?>${item.image}" alt="${item.product_name}" style="width: 50px; height: 50px; object-fit: cover;">
                                    </li>
                                `;
                            });
                        } else {
                            detailHtml += `<li>Tidak ada item dalam pesanan ini.</li>`;
                        }
                        detailHtml += `</ul><hr>`;

                        if (order.payment) {
                            detailHtml += `
                                <h5>Detail Pembayaran:</h5>
                                <p><strong>Trx ID:</strong> ${order.payment.transaction_id || 'Belum Dibayar'}</p>
                                <p><strong>Metode Pembayaran:</strong> ${order.payment.payment_method || 'Belum Dibayar'}</p>
                                <p><strong>Status Pembayaran:</strong> <span class="badge ${getBadgeClass(order.payment.payment_status)}">${order.payment.payment_status || 'Pending'}</span></p>
                                <p><strong>Total Pembayaran:</strong> Rp ${new Intl.NumberFormat('id-ID').format(order.payment.total_payment || order.total_amount || 0)}</p>
                            `;
                        } else {
                            detailHtml += `<h5>Pembayaran:</h5><p>Belum ada informasi pembayaran.</p>`;
                        }
                        
                        modal.find('.modal-body').html(detailHtml);
                    } else {
                        modal.find('.modal-body').html('<p class="text-danger">Gagal memuat detail pesanan.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    modal.find('.modal-body').html('<p class="text-danger">Terjadi kesalahan saat memuat detail.</p>');
                    console.error("AJAX Error:", status, error, xhr.responseText);
                }
            });
        });

        // Fungsi helper untuk mendapatkan kelas badge (sesuai dengan yang ada di tabel)
        function getBadgeClass(status) {
            switch (status ? status.toLowerCase() : '') {
                case 'completed':
                case 'success':
                case 'paid':
                    return 'bg-success';
                case 'pending':
                    return 'bg-warning';
                case 'failed':
                case 'cancelled':
                case 'batal':
                    return 'bg-danger';
                default:
                    return 'bg-secondary';
            }
        }
    });
</script>

<?= $this->endSection() ?>