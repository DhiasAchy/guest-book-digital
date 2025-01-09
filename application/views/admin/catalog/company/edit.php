
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
								<h4 class="mt-0 header-title">Edit Company</h4>
								<!--<form class="mb-0" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/update'; ?>" id="form-add" method="POST" enctype="multipart/form-data">-->
							    <?= form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/update'); ?>
								    <input type="hidden" name="id" value="<?= $company['id'] ?>">
								    
								    <div class="row">
								        <div class="col-12">
								            <div class="form-group bmd-form-group">
        										<label for="InputName">Nama Company</label>
        										<input type="text" class="form-control" name="company" value="<?= $company['name'] ?>" id="InputName">
        									</div>
								        </div>
								        <div class="col-12 col-lg-4">
								            <div class="form-group bmd-form-group">
        										<img src="<?= base_url($company['image']) ?>" style="width:200px" alt="" srcset="">
        									</div>
								            <div class="form-group bmd-form-group">
        										<label for="InputImage">Logo</label>
        										<input type="file" class="form-control" name="image" value="" id="InputImage">
        									</div>
								        </div>
								        <div class="col-12 col-lg-4">
								            <div class="form-group bmd-form-group">
        										<img src="<?= base_url($company['image_cover']) ?>" style="width:100px" alt="" srcset="">
        									</div>
								            <div class="form-group bmd-form-group">
        										<label for="InputImageThumbnail">Thumbnail PDF</label>
        										<input type="file" class="form-control" name="image_cover" value="" id="InputImageThumbnail">
        									</div>
								        </div>
								        <div class="col-12 col-lg-4">
								            <?php 
								                if($company['pdf']!='' || $company['pdf']!=null) { ?>
								                    <div class="form-group bmd-form-group">
                										<a href="<?= base_url($company['pdf']); ?>" target="_blank">View</a>
                									</div><?php
								                }
								            ?>
								            
								            <div class="form-group bmd-form-group">
        										<label for="InputPDF">PDF</label>
        										<input type="file" class="form-control" name="file_name" value="" id="InputPDF">
        									</div>
								        </div>
								    </div>

									<button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button>
									<a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)); ?>" class="btn btn-raised btn-danger mb-0">Cancel</a>
								</form>
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