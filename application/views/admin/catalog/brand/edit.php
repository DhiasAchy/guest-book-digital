
<!-- Content -->

<div class="content-page">
	<!-- Start content -->
	<div class="content">

		<div class="page-content-wrapper ">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 mb-3">
					   <div class="card">
					       <div class="card-body">
					           <h5 class="mt-0 header-title float-left">Edit Brand</h5>
					           
					           <div class="float-right">
								    <button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button>
									<a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)); ?>" class="btn btn-raised btn-danger mb-0">Cancel</a>
								</div>
					       </div>
					    </div>
					</div>
					
					<div class="col-12">
				       <form class="mb-0" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/update'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
    					    <input type="hidden" name="id" value="<?= $brand['id'] ?>">
    					    
    					    <div class="row">
    					        <div class="col-12 mb-3">
            				        <div class="card shadow">
            				            <div class="card-body">
            				                <div class="form-group bmd-form-group">
            									<label for="exampleInputEmail1" class="bmd-label-floating ">Nama Brand</label>
            									<input type="text" class="form-control" name="brand" value="<?= $brand['name'] ?>" id="InputName">
            								</div>
            				            </div>
            				        </div>
                				</div>
                				<div class="col-12 col-sm-6 col-lg-4 mb-3">
            				        <div class="card shadow">
            				            <div class="card-header">Image Brand</div>
            				            <div class="card-body">
            				                <div class="form-group bmd-form-group text-center">
        										<img src="<?= base_url($brand['image']) ?>" style="width:100px" alt="" srcset="">
        									</div>
        						            <div class="form-group bmd-form-group">
        										<input type="file" class="form-control" name="image" value="" id="InputImage">
        									</div>
            				            </div>
            				        </div>
                				</div>
                				<div class="col-12 col-sm-6 col-lg-4 mb-3">
            				        <div class="card shadow">
            				            <div class="card-header">Title Brand</div>
            				            <div class="card-body">
            				                <div class="form-group bmd-form-group text-center">
        										<img src="<?= base_url($brand['image_title']); ?>" style="width:100px" alt="" srcset="">
        									</div>
        						            <div class="form-group bmd-form-group">
        										<input type="file" class="form-control" name="image_title" value="" id="InputImageTitle">
        									</div>
            				            </div>
            				        </div>
                				</div>
                				<div class="col-12 col-sm-6 col-lg-4 mb-3">
            				        <div class="card shadow">
            				            <div class="card-header">Pdf Brand <br><small>* hanya diisi jika catalog brand hanya 1.</small></div>
            				            <div class="card-body">
            				                <div class="form-group bmd-form-group text-center">
            				                    <?php 
            				                        if($brand['pdf']!=null) { ?>
            				                            <a href="<?= base_url().$brand['pdf']; ?>">
            				                                <img src="<?= base_url('/assets/images/icon-pdf.png'); ?>" style="width:80px" alt="" srcset="">
            				                            </a>
            				                            <?php
            				                        } else { ?>
            				                            <img src="<?= base_url('/assets/images/icon-pdf.png'); ?>" style="width:80px; filter: grayscale(90%);" alt="" srcset="">
            				                            <?php
            				                        }
            				                    ?>
        									</div>
        						            <div class="form-group bmd-form-group">
        										<input type="file" class="form-control" name="file_name" value="" id="InputPDF">
        									</div>
            				            </div>
            				        </div>
                				</div>
    					    </div>
    					</form>
					</div>
					
					<div class="col-12">
					    <span>*kosongkan tombol upload file jika tidak akan mengubah sesuatu.</span>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- End Content -->

<script>
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