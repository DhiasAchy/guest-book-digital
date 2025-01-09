<!-- Content -->
<?php $this->load->model('Model_catalog_brand'); ?>

<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Data Sales</h4>
                                <a class="btn btn-primary border-0" href="<?= base_url(uri_string() . '/add'); ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                    <div class="ripple-container"></div>
                                </a>
                                <hr>
                                <table id="datatable" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Brand</th>
                                            <th>Telepon</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 0;
                                        foreach ($sales as $key => $value) {
                                            $no++ ?>
                                            <tr id="<?= $value['id']; ?>">
                                                <td><?= $no; ?></td>
                                                <td><?= $value['code']; ?></td>
                                                <td><?= $value['name']; ?></td>
                                                <td>
                                                    <ul>
                                                        <?php 
                                                            $array_brand = json_decode($value['brand_id']);
                                                            foreach($array_brand as $key => $val) { ?>
                                                                <li><?= $val; ?></li> <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </td>
                                                <td><?= $value['phone']; ?></td>
                                                <td><?= $value['created_at']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/user/sales/edit/' . $value['id'] . ''); ?>" class="btn btn-primary border-0">
                                                        <i class="fas fa-edit"></i>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                    <button type="button" class="btn btn-danger border-0 remove">
                                                        <i class="fa fa-trash"></i>
                                                        <div class="ripple-container"></div>
                                                    </button>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- End Content -->

<script type="text/javascript">
    $(".remove").click(function() {
        var id = $(this).parents("tr").attr("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: '<?= base_url(); ?>admin/user/sales/delete/' + id + '',
                    type: 'POST',
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        $("#" + id).remove();
                        swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    }
                });
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
    });
</script>