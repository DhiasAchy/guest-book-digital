<!-- Content -->

<div class="content-page">
	<div class="container-fluid">
		<div class="row">
		    <div class="col-12 mb-3">
				<div class="card shadow mb-2">
					<div class="card-body">
						<h5 class="mt-0 header-title float-left">Detail Event</h5>
						
						<div class="float-right">
						    <a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)); ?>" class="btn btn-raised btn-secondary mb-0">Kembali</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 mb-3">
			    <div class="card shadow mb-2">
			        <div class="card-header">Event</div>
			        <div class="card-body text-center">
			            <img src="<?= base_url($event['image']) ?>" style="" alt="" srcset="" class="img-fluid">
			        </div>
			        <div class="card-body">
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="row">
									<div class="col-12 row">
										<div class="col-6">Kode Event</div>
										<div class="col-6"><?= $event['event_code'] ;?></div>
									</div>
									<div class="col-12 row">
										<div class="col-6">Link Pendaftaran Event</div>
										<div class="col-6 mb-2">
											<a href="<?= base_url('/homepage/register/'.$event['event_code']); ?>" target="_blank"><div id="copyDiv"><?= base_url('/homepage/register/'.$event['event_code']); ?></div></a> <br>
											<button class="btn btn-primary copy ms-3" onclick="copyTextFromElement('copyDiv')" title="Copy Link">
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
													<path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
												</svg>
											</button>
											<a href="<?= base_url('/homepage/register/'.$event['event_code']); ?>" target="_blank">
												<button class="btn btn-primary copy ms-3" title="Buka Link">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link" viewBox="0 0 16 16">
														<path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9q-.13 0-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
														<path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4 4 0 0 1-.82 1H12a3 3 0 1 0 0-6z"/>
													</svg>
												</button>
											</a>

											<div class="copied-msg" id="copiedMsg" style="display:none;">Teks berhasil disalin!</div>
										</div>
									</div>
									<div class="col-12 row">
										<div class="col-6">Nama Event</div>
										<div class="col-6"><?= $event['name'] ;?></div>
									</div>
									<div class="col-12 row">
										<div class="col-6">Alamat Event</div>
										<div class="col-6"><?= $event['event_address'] ;?></div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6 mt-3">
								<div class="row">
									<div class="col-12 row">
										<div class="col-6">Tanggal Event</div>
										<div class="col-6"><?= date("j F Y", strtotime($event['event_date'])); ?></div>
									</div>
									<div class="col-12 row">
										<div class="col-6">Status Event</div>
										<div class="col-6"><?= event_status($event['event_date']); ?></div>
									</div>
								</div>
							</div>
						</div>
			        </div>
			        
			    </div>
			</div>
			<div class="col-12 mb-3">
				<form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -3)) . '/event/send_email/'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
					<div class="card shadow mb-2">
						<div class="card-header">
							Peserta Event
							<div class="float-right">
								<a class="btn btn-primary border-0" href="<?= base_url(array_slice(explode('/', uri_string()), 0, -3)) . '/event/add_guest/'.$event['id']; ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
									<div class="ripple-container"></div>
								</a>
								<button type="submit" id="submit" class="btn btn-primary btn-raised mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
										<path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671"></path>
										<path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791"></path>
									</svg>
								</button>
							</div>
						</div>
						<div class="card-body table-responsive">
							<table id="datatable" class="table table-striped nowrap dataTable">
								<thead>
									<tr>
										<th scope="col" class="text-center align-middle">
											<div class="form-check">
												<input type="checkbox" class="form-check-input checkAll" id="checkAll">
											</div><br>
											Pilih Semua
										</th>
										<th scope="col" class="text-center">No</th>
										<th scope="col" class="text-center">Nama</th>
										<th scope="col" class="text-center">Alamat</th>
										<th scope="col" class="text-center">QR</th>
										<th scope="col" class="text-center">Waktu Hadir</th>
										<th scope="col" class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody id="table_row">
									<?php $no = 0;
										// print_r($guest_);
										foreach ($guest_ as $key => $value) { $no++; ?>
											<tr>
												<td class="text-center align-top" style="width: 50px;">
													<div class="form-check">
														<input type="checkbox" class="form-check-input" id="InputCheck" name="InputCheck[]" value="<?= $value['id'];?>">
													</div>
												</td>
												<td class="text-center"><?= $no; ?></td>
												<td class="text-center"><?= $value['guest_name']; ?></td>
												<td class="text-center"><?= $value['address']; ?></td>
												<td class="text-center">
													<a href="<?= base_url('assets/img/qrcode/'.$value['qrcode_path']) ?>" target="_blank" class="text-decoration-none">
														<img src="<?= base_url('assets/img/qrcode/'.$value['qrcode_path']) ?>" width="100" alt="<?= $value['qrcode_data']; ?>"> <br>
														<span class="fw-bolder"><?= $value['qrcode_data']; ?></span>
													</a>
												</td>
												<td class="text-center"><?= ($value['event_attendance'] == '') ? "-" : $value['event_attendace']; ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-danger border-0 remove">
														<i class="fa fa-trash"></i>
														<div class="ripple-container"></div>
													</button>
												</td>
											</tr>
											<?php
										}
									?>
								</tbody>
							</table>  
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Content -->

<script>
	async function copyTextFromElement(elementId) {
		const el = document.getElementById(elementId);
		const text = el.innerText;

		try {
			await navigator.clipboard.writeText(text);
			document.getElementById('copiedMsg').style.display = 'block';

			setTimeout(() => {
			document.getElementById('copiedMsg').style.display = 'none';
			}, 1500);
		} catch (err) {
			alert('Gagal menyalin teks: ' + err);
		}
	}

	$(document).ready(function() {
		// checked all
		$(".checkAll").click(function () {
			$('input:checkbox').not(this).prop('checked', this.checked);
		});

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