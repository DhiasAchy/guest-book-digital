
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
								<h4 class="mt-0 header-title">Edit Sales</h4>
								<form class="mb-0" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/update'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
								    <input type="hidden" name="id" value="<?= $sales['id'] ?>">
								    
								    <div class="row">
								        <div class="col-12">
								            <div class="form-group bmd-form-group">
        										<label for="InputName">Nama Sales</label>
        										<input type="text" class="form-control" name="name" value="<?= $sales['name'] ?>" id="InputName">
        									</div>
								        </div>
								        <div class="col-12 col-lg-6">
								            <div class="form-group bmd-form-group">
        										<label for="InputCode">Kode Sales</label>
        										<input type="text" class="form-control" name="code" value="<?= $sales['code'] ?>" id="InputCode">
        									</div>
								        </div>
								        <div class="col-12 col-lg-6">
								            <div class="form-group bmd-form-group">
        										<label for="InputPhone">Telepon</label>
        										<input type="text" class="form-control" name="phone" value="<?= $sales['phone'] ?>" id="InputPhone">
        									</div>
								        </div>
								        <div class="col-12 col-lg-6">
								            <div class="form-group bmd-form-group">
        										<label for="SelectBrand" class="bmd-label-floating ">Brand</label>
        										<?php 
        										    $array_brand = json_decode($sales['brand_id']);
        										?>
        										<select class="form-control" name="brand[]" required="" id="InputBrand" multiple="multiple">
        										    <option value="0">-- Pilih Brand --</option>
        										    <?php foreach($brand as $key => $value) : ?>
        										        <option value="<?= $value['id']; ?>" <?= (in_array($value['id'], $array_brand)) ? 'selected' : '' ?> ><?= $value['name']; ?></option>
        										    <?php endforeach; ?>
                                                </select>
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
	    $('#InputBrand').select2();
	    
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