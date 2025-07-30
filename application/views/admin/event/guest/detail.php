<!-- Content -->

<div class="content-page">
	<div class="container-fluid">
		<div class="row">
		    <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
		    <?php /*form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert');*/ ?>
    			<div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    						<h5 class="mt-0 header-title float-left">Detail Peserta</h5>
    						
    						<div class="float-right">
    						    <!-- <button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button> -->
    						    <!-- <button type="button" id="cancel" class="btn btn-secondary btn-raised mb-0">Kembali</button> -->
    							<a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -4)) . '/event/guest/'; ?>" class="btn btn-raised btn-secondary mb-0">Kembali</a>
    						</div>
    					</div>
    				</div>
    			</div>
    			
    			<div class="col-12 mb-3">
    				<div class="card shadow mb-3">
    					<div class="card-body">
							<div class="table-responsive">
								<table class="table table-borderless">
									<tr>
										<td>Nama</td>
										<td>: <?= $guest['name']; ?></td>
									</tr>
									<tr>
										<td>Email</td>
										<td>: <?= $guest['email']; ?></td>
									</tr>
									<tr>
										<td>Telepon</td>
										<td>: <?= $guest['phone']; ?></td>
									</tr>
									<tr>
										<td>Alamat</td>
										<td>: <?= $guest['address']; ?></td>
									</tr>
									<tr>
										<td>Event</td>
										<td>: 
											<?php 
												foreach ($guest['event_data'] as $key => $value) {
													echo '<span class="badge bg-secondary text-white mx-1 px-2">'.$value['name'].'</span>';
												}
											?>
										</td>
									</tr>
									<tr>
										<td>Qr Code</td>
										<td>: <a href="<?= base_url('assets/img/qrcode/'.$guest['qrcode_path']) ?>" target="_blank" class="text-decoration-none text-dark fw-bold"><img src="<?= base_url('assets/img/qrcode/'.$guest['qrcode_path']) ?>" width="100" alt="<?= $guest['qrcode_data']; ?>"><?= $guest['qrcode_data']; ?></a></td>
									</tr>
								</table>
							</div>
    			        </div>
    			    </div>
    		    </div>
			</form>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card shadow mb-2 mx-3">
					<div class="card-header">Log Peserta</div>
					<div class="card-body table-responsive">
						<table id="datatable" class="table table-striped nowrap dataTable">
							<thead>
								<tr>
									<th scope="col" class="text-center">No</th>
									<th scope="col" class="text-center">Event</th>
									<th scope="col" class="text-center">Waktu Hadir</th>
								</tr>
							</thead>
							<tbody id="table_row">
								<?php 
									$no = 1;
									foreach ($guest['log'] as $key => $value) { ?>
										<td class="text-center"><?= $no; ?></td>
										<td class="text-center"><?= $value['event_name']; ?></td>
										<td class="text-center"><?= $value['created_at']; ?></td>
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