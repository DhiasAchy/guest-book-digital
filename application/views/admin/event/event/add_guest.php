<div class="content-page">
	<div class="container-fluid">
		<div class="row">
		    <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/insert_guest'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
		    <?php /*form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert');*/ ?>
    			<div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    						<h5 class="mt-0 header-title float-left">Tambah Peserta Event</h5>
    						
    						<div class="float-right">
                                <input type="hidden" name="event_id" value="<?= $event_id; ?>">
    						    <button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button>
    						    <button type="button" id="cancel" class="btn btn-secondary btn-raised mb-0" onclick="history.back()">Kembali</button>
    							<!-- <a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)); ?>" class="btn btn-raised btn-secondary mb-0">Kembali</a> -->
    						</div>
    					</div>
    				</div>
    			</div>

                <div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    					    <div class="row">
                                <div class="col-12">
                                    <div class="form-group bmd-form-group">
                                        <label for="exampleInputName">Peserta Event</label>
                                        <select class="form-control js-example-basic-multiple" name="states[]" id="" multiple="multiple">
                                            <?php 
                                                foreach ($guest as $key => $value) { ?>
                                                    <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
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

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            placeholder: "Silahkan pilih peserta, bisa lebih dari 1"
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