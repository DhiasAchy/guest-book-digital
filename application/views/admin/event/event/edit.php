<!-- Content -->

<div class="content-page">
	<div class="container-fluid">
		<div class="row">
		    <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/update'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
		    <?php /*form_open_multipart(base_url(array_slice(explode('/', uri_string()), 0, -1)) . '/insert');*/ ?>
    			<div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    						<h5 class="mt-0 header-title float-left">Edit Event</h5>
    						
    						<div class="float-right">
    						    <input type="hidden" name="id" value="<?= $event['id'] ?>">
    						    <button type="submit" id="submit" class="btn btn-success btn-raised mb-0">Submit</button>
    							<a href="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)); ?>" class="btn btn-raised btn-secondary mb-0">Kembali</a>
    						</div>
    					</div>
    				</div>
    			</div>
    			
    			<div class="col-12 mb-3">
    				<div class="card shadow m-b-30">
    					<div class="card-body">
    					    <div class="row">
    			                <div class="col-6">
        							<div class="row">
										<div class="col-12">
            			                    <div class="form-group bmd-form-group">
                								<label for="exampleInputName">Kode Event</label>
                								<input type="text" class="form-control" name="code" value="<?= $event['event_code']; ?>" id="InputName">
                							</div>
            			                </div>
            			                <div class="col-12">
            			                    <div class="form-group bmd-form-group">
                								<label for="exampleInputName">Nama Event</label>
                								<input type="text" class="form-control" name="event" value="<?= $event['name']; ?>" id="InputName">
                							</div>
            			                </div>
            			                <div class="col-12 mb-3">
                        		            <div class="form-group bmd-form-group">
                								<label for="exampleInputDate">Tanggal Event</label>
                								<input type="date" class="form-control" name="date" id="" value="<?= date("Y-m-d",strtotime($event['event_date'])); ?>" id="InputDate">
                							</div>
                        		        </div>
                        		        <div class="col-12 mb-3">
                        		            <div class="form-group bmd-form-group">
        										<label for="exampleInputEmail1" class="bmd-label-floating ">Alamat Event</label>
        										<textarea name="address" id="InputAddress" class="form-control" rows="5"><?= $event['event_address'] ?></textarea>
        									</div>
                        		        </div>
            			            </div>
    			                </div>
    			                <div class="col-6"><div>
    			                    <div class="row">
    			                        <div class="col-12">
        				                    <div class="form-group bmd-form-group">
        				                        <label for="InputImage">Thumbnail Event</label>
        										<input type="file" class="form-control" name="image" value="" id="InputImage">
												<input type="hidden" name="file_image" value="<?= $event['image']; ?>">
        									</div>
        									<div class="form-group bmd-form-group text-center">
        										<a href="<?= base_url().$event['image']; ?>" target="_blank"><img src="<?= base_url().$event['image']; ?>" style="" class="img-fluid" alt="<?= $event['image']; ?>" srcset=""></a>
        									</div>
    			                        </div>
    			                    </div>
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
					// console.log(data.error);
					$('.form-group').removeClass('has-error');
					$('.text-danger').remove();
					if (data.error) {
						if(data.form_validation){
							for (i in data.msg) {
								$('input[name=\'' + i + '\']').closest('.form-group').addClass('has-error');
								$('input[name=\'' + i + '\']').after('<small class="text-danger"><i>' + data.msg[i] + '</i></small>');
							}
						}else{
							swal("Oops...", data.msg, "error");
						}
					} else if (!data.error) {
						swal("Success!",data.msg, "success")
							.then((value) => {
								// window.location.replace(data.redirect);
								window.location.reload();
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

		// $('#datepicker').datepicker("setDate", new Date(2008,9,03) );
		// $('#datePicker').val(toDateInputValue(new Date()));

		// $(".datee").datepicker("setDate", "-7");
		// $("#datepicker11").datepicker("setDate", <?= date("Y/m/d",strtotime($event['event_date'])); ?>).trigger("change");
	});
</script>