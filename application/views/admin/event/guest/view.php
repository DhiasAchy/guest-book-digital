

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
                                <h4 class="mt-0 header-title">Data Peserta</h4>
                                <a class="btn btn-primary border-0" href="<?= base_url(uri_string() . '/add'); ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                    <div class="ripple-container"></div>
                                </a>
                                <a class="btn btn-success border-0" href="<?= base_url(uri_string() . '/import'); ?>" role="button" title="Import Excel / Csv">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel" viewBox="0 0 16 16">
                                        <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                    </svg>
                                </a>
                                <hr>
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered w-100 nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Event</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">QR Code</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php $no = 1;
                                            foreach ($guest as $key => $value) { ?>
                                                <tr id="<?= $value['id']; ?>">
                                                    <td class="text-center" width="60px"><?= $no; ?></td>
                                                    <td>
                                                        <?php 
                                                            foreach ($value['event_data'] as $key1 => $value1) {
                                                                echo '<span class="badge bg-secondary text-white mx-1 px-2">'.$value1['name'].'</span>';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?= $value['name']; ?></td>
                                                    <td class="text-center"><a href="<?= base_url('assets/img/qrcode/'.$value['qrcode_path']) ?>" target="_blank"><img src="<?= base_url('assets/img/qrcode/'.$value['qrcode_path']) ?>" width="100" alt="<?= $value['qrcode_path']; ?>"></a></td>
                                                    <td><?= $value['address']; ?></td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('admin/event/guest/detail/' . $value['id'] . ''); ?>" class="btn btn-secondary border-0">
                                                            <i class="fa fa-eye"></i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        <a href="<?= base_url('admin/event/guest/edit/' . $value['id'] . ''); ?>" class="btn btn-primary border-0"><i class="fas fa-edit"></i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        <button type="button" class="btn btn-danger border-0 remove">
                                                            <i class="fa fa-trash"></i>
                                                            <div class="ripple-container"></div>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php $no++;
                                            }
                                        ?>
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
            title: "Are you sure?" + id,
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