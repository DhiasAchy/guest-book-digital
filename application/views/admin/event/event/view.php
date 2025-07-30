

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
                                <h4 class="mt-0 header-title">Data Event</h4>
                                <a class="btn btn-primary border-0" href="<?= base_url(uri_string() . '/add'); ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                    <div class="ripple-container"></div>
                                </a>
                                <hr>
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered w-100 nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Tanggal Event</th>
                                                <th class="text-center">Nama Event</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $no = 0;
                                            foreach ($event as $key => $value) {
                                                $no++ ?>
                                                <tr id="<?= $value['id']; ?>">
                                                    <td class="text-center" width="60px"><?= $value['id']; ?></td>
                                                    <td><?= date("j F Y",strtotime($value['event_date'])); ?></td>
                                                    <td><?= $value['name']; ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            $date = date('Ymd', strtotime($value['event_date']));
                                                            $date_ = strtotime($value['event_date']);
                                                            $date_today = date('Ymd');
                                                            $remaining = $date_ - time();
                                                            $days_remaining = floor($remaining / 86400);
                                                            $hours_remaining = floor(($remaining % 86400) / 3600);
                                                            // echo "There are $days_remaining days and $hours_remaining hours left";
                                                            
                                                            if($date>$date_today) {
                                                                $status_color = "primary";
                                                                $status_text = "Event Belum Mulai, Masih ".$days_remaining." hari, ".$hours_remaining." jam lagi";
                                                            } else if($date<$date_today) {
                                                                $status_color = "danger";
                                                                $status_text = "Event Sudah Selesai";
                                                            } else {
                                                                $status_color = "success";
                                                                $status_text = "Event Sedang Berlangsung";
                                                            }
                                                        ?>
                                                        <button type="button" class="btn btn-outline-<?= $status_color; ?>"><?= $status_text; ?></button>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('admin/event/event/detail/' . $value['id'] . ''); ?>" class="btn btn-secondary border-0">
                                                            <i class="fa fa-eye"></i>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                        <a href="<?= base_url('admin/event/event/edit/' . $value['id'] . ''); ?>" class="btn btn-primary border-0"><i class="fas fa-edit"></i>
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