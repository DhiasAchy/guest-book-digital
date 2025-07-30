<!-- content -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <?php 
                                    //echo hash('sha512', '#ebook#2024')."<br>";
                                ?>
                                <h4 class="mt-0 header-title">Daftar Catalog Brand <?= $brand['name']; ?></h4>
                                <!-- <a class="btn btn-primary border-0" href="<?= base_url(uri_string() . '/add'); ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                    <div class="ripple-container"></div>
                                </a> -->
                                <button type="button" class="btn btn-sm btn-success" onclick="modal(this)" data-id="<?= $brand['id']; ?>">
                                    <i class="fa fa-upload"></i>
                                    Upload
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-secondary float-right" onclick="history.back()">
                                    Kembali
                                </button>
                                <hr>
                                <table id="datatable" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th class="text-center">Pdf</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 0;
                                        foreach ($catalog as $key => $value) {
                                            $no++ ?>
                                            <tr id="<?= $value['id']; ?>">
                                                <td><?= $value['id']; ?></td>
                                                <td><?= $value['name']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url($value['file']) ?>" target="_blank">
                                                        <img src="<?= base_url($value['image']) ?>" width="50" alt="">
                                                    </a>
                                                    
                                                </td>
                                                <td><?= $value['created_at']; ?></td>
                                                <td class="text-center">
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

<!-- modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!--<form class="mb-0" action="<?= base_url(uri_string()) . '/upload_catalog'; ?>" id="form-add" method="POST" enctype="multipart/form-data">-->
        <?= form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/upload_catalog'); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle2">Upload Pdf Catalog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" name="brand_id" id="brand_id">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group bmd-form-group">
        						<label for="InputName">Nama Catalog</label>
        						<input type="text" class="form-control" name="name" value="" id="InputName">
        					</div>
                        </div>
                        <div class="col-6">
                            <div class="form-group bmd-form-group">
        						<label for="InputPDF">PDF</label>
        						<input type="file" class="form-control" name="file_name" value="" id="InputPDF">
        					</div>
                        </div>
                        <div class="col-6">
                            <div class="form-group bmd-form-group">
        						<label for="InputThumbnail">Thumbnail</label>
        						<input type="file" class="form-control" name="image_cover" value="" id="InputThumbnail">
        					</div>
                        </div>
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

<script>
    function modal(el) {
        const id = $(el).attr('data-id');
        $('#brand_id').val(id);
        $('#exampleModalCenter').modal('show');
    }
    
    $(document).ready(function() {
        var data_error = <?= $return['error']; ?>;
        var url_reload = "https://wirawan.web.id/ebook/ci/admin/catalog/brand/detail/<?= $brand['id']; ?>";
        
        // alert(data_error);
        
        if(data_error==0) {
            swal("Success!", "Berhasil", "success")
            .then((value) => {
                window.location.replace(url_reload);
            });
        } 
        
        if(data_error==1) {
            swal("Error!", "Terjadi Kesalahan", "error")
            /*.then((value) => {
                window.location.replace(url_reload);
            });*/
        }
        
        <?php 
            /*if($return!='3') { ?>
                // alert('beda');
                // console.log(<?$return; ?>)
                var data_error = <?= $return['error']; ?>
                if(data_error==0) {
                    swal("Success!", "<?= $return['msg']; ?>", "success")
                    .then((value) => {
                        window.location.replace("https://wirawan.web.id/ebook/ci/admin/catalog/brand/detail/<?= $brand['id']; ?>");
                    });
                }
                <?php
            } else { ?>
                // alert('sama');
                <?php
            }*/
        ?>
    });
</script>

<script type="text/javascript">
    $(".remove").click(function() {
        var id = $(this).parents("tr").attr("id");
        
        // alert("<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/delete_catalog/'; ?>"+id);
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: '<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/delete_catalog/'; ?>' + id + '',
                    type: 'POST',
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        $("#" + id).remove();
                        swal("Deleted!", "Your data has been deleted.", "success");
                    }
                });
            } else {
                swal("Cancelled", "Your data is safe :)", "error");
            }
        });
    });
</script>