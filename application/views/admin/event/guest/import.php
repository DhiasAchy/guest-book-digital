<!-- Content -->

<div class="content-page">
	<div class="container-fluid">
		<div class="row">
		    <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/import_process'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
		    <?php /*form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert');*/ ?>
    			<div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    						<h5 class="mt-0 header-title float-left">Import Peserta</h5>
    						
    						<div class="float-right">
    						    <button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button>
    						    <!-- <button type="button" id="cancel" class="btn btn-secondary btn-raised mb-0">Kembali</button> -->
    							<a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -3)) . '/event/guest/'; ?>" class="btn btn-raised btn-secondary mb-0">Kembali</a>
    						</div>
    					</div>
    				</div>
    			</div>
    			
    			<div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    					    <div class="row">
								<div class="col-12 col-md-8">
									<div class="form-group bmd-form-group">
										<label for="exampleInputName">Pilih File Excel &nbsp;

											<a class="btn btn-secondary border-0" href="<?= base_url('assets/demo/Template Import Guest.xlsx'); ?>" role="button" title="Download template">
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel" viewBox="0 0 16 16">
													<path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
													<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
												</svg>
											</a>
										</label>
										<input type="file" class="form-control pb-5 pt-3" name="fileExcel" id="fileExcel">
									</div>
								</div>
							</div>

							<div class="row d-none">
								<div class="col-12">
									<div class="table-responsive">
										<table class="table table-striped nowrap" id="datatable">
											<thead>
												<tr>
													<th>No</th>
													<th>Nama</th>
													<th>Email</th>
													<th>No Telepon</th>
													<th>Alamat</th>
												</tr>
											</thead>
											<tbody>
												<?php ?>
											</tbody>
										</table>
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
				error: function(data) {
					swal("Success!", "data saved successfully", "success");
				}
			});
		});
	});
</script>