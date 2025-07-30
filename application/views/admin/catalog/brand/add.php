
<!-- Content -->

<div class="content-page">
	<!-- Start content -->
	<div class="content">

		<div class="page-content-wrapper ">
			<div class="container-fluid">
				<div class="row">
				    <form class="mb-0" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
				    <?php /*form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert');*/ ?>
					<div class="col-12 mb-3">
						<div class="card shadow m-b-30">
							<div class="card-body">
								<h5 class="mt-0 header-title float-left">Tambah Brand</h5>
								
								<div class="float-right">
								    <button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button>
									<a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)); ?>" class="btn btn-raised btn-secondary mb-0">Kembali</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-12">
					   <div class="row">
						        <div class="col-12 mb-3">
						            <div class="card shadow">
						                <div class="card-body">
						                    <div class="form-group bmd-form-group">
        										<label for="exampleInputEmail1">Nama Brand</label>
        										<input type="text" class="form-control" name="brand" value="" id="InputName">
        									</div>
						                </div>
						            </div>
						        </div>
						        <div class="col-12 col-sm-6 col-lg-4 mb-3">
						            <div class="card shadow">
						                <div class="card-header">Image Brand</div>
						                <div class="card-body">
						                    <div class="form-group bmd-form-group text-center">
        										<img src="<?= base_url('/assets/images/icon-image.png'); ?>" style="width:100px" alt="" srcset="">
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
        										<img src="<?= base_url('/assets/images/icon-image.png'); ?>" style="width:100px" alt="" srcset="">
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
        										<img src="<?= base_url('/assets/images/icon-pdf.png'); ?>" style="width:80px" alt="" srcset="">
        									</div>
        						            <div class="form-group bmd-form-group">
        										<input type="file" class="form-control" name="file_name" value="" id="InputPDF">
        									</div>
						                </div>
						            </div>
						        </div>
						    </div>
					</div>
					</form>
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
			console.log("post data ...");
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