<!-- Content -->

<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Data Achievement</h4>
                                <a class="btn btn-primary border-0" href="<?= base_url(uri_string() . '/add'); ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                    <div class="ripple-container"></div>
                                </a>
                                <hr>
                                <table id="datatable" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 0;
                                        foreach ($achievement as $key => $value) {
                                            $no++ ?>
                                            <tr id="<?= $value['id']; ?>">
                                                <td><?= $no; ?></td>
                                                <td><?= $value['name']; ?></td>
                                                <td class="text-center"><img src="<?= base_url($value['image']) ?>" width="50" alt=""></td>
                                                <td><?= $value['created_at']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/achievement/edit/' . $value['id'] . ''); ?>" class="btn btn-primary border-0">
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
                    url: '<?= base_url(); ?>admin/achievement/delete/' + id + '',
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