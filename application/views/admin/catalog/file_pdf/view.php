

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
                                <h4 class="mt-0 header-title">Data File PDF</h4>
                                <a class="btn btn-primary border-0" href="<?= base_url(uri_string() . '/add'); ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                    <div class="ripple-container"></div>
                                </a>
                                <hr>
                                <table id="datatable" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th class="text-center">Company</th>
                                            <th class="text-center">Brand</th>
                                            <th class="text-center">Pdf</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 0;
                                        foreach ($pdf as $key => $value) {
                                            $no++ ?>
                                            <tr id="<?= $value['id']; ?>">
                                                <td><?= $value['id']; ?></td>
                                                <td><?= $value['name']; ?></td>
                                                <td><?= $value['company_id']; ?></td>
                                                <td><?= $value['brand_id']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url($value['file']) ?>" target="_blank">
                                                        <img src="<?= base_url($value['image']) ?>" width="50" alt=""></td>
                                                    </a>
                                                <td><?= $value['created_at']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/catalog/file_pdf/edit/' . $value['id'] . ''); ?>" class="btn btn-primary border-0"><i class="fas fa-edit"></i>
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mb-0" action="<?= base_url(uri_string()) . '/upload_catalog'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle2">Upload File Catalog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" name="brand_id" id="brand_id">
                    <div class="form-group bmd-form-group">
                        <label for="exampleInputEmail1">File catalog</label>
                        <input type="file" class="form-control" name="file_catalog" value="" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-primary" id="submit">Submit</button>
                    <button type="button" class="btn btn-raised btn-danger ml-2" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Content -->
<script>
    function modal(el) {
        const id = $(el).attr('data-id');
        $('#id').val(id);
        $('#exampleModalCenter').modal('show');
    }
    $(document).ready(function() {
        $('#form-add').on('submit', function(e) {
            e.preventDefault();
            var btn = $('#submit');
            $.ajax({
                url: $(this).attr('action'), //nama action script php sobat
                data: new FormData(this),
                type: 'POST',
                dataType: 'json',
                processData: false,
                cache: false,
                contentType: false,
                beforeSend: function() {
                    // btn.button('loading');
                    btn.html('Loading...');
                },
                complete: function() {
                    btn.html('Submit');
                },
                success: function(data) {
                    console.log(data.error);
                    $('.form-group').removeClass('has-error');
                    $('.text-danger').remove();
                    if (data.error) {
                        if (data.form_validation) {
                            for (i in data.msg) {
                                $('input[name=\'' + i + '\']').closest('.form-group').addClass('has-error');
                                $('input[name=\'' + i + '\']').after('<small class="text-danger"><i>' + data.msg[i] + '</i></small>');
                            }
                        } else {
                            swal("Oops...", data.msg, "error");
                        }
                    } else if (!data.error) {
                        swal("Success!", data.msg, "success")
                            .then((value) => {
                                window.location.replace(data.redirect);
                            });
                    } else {
                        swal("Oops...", data.msg, "error");
                    }
                },
                error: function(data) {
                    swal("Oops...", "Something went wrong :(", "error");
                }
            });
        });
    });
</script>
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
                    url: '<?= base_url(uri_string() . '/delete/'); ?>' + id + '',
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