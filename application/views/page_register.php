<div class="container">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form class="form" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/register_process/'.$event_code; ?>" id="form-add" method="POST" enctype="multipart/form-data">
        <img src="assets/images/logo-wirawan.png" class="img-fluid p-5" alt="">
        <h1 class="h3 text-white text-center">Pendaftaran Peserta</h1>

        <label for="name" class="label">Nama Lengkap</label>
        <input type="text" class="input" placeholder="Nama lengkap" name="name" id="name">

        <label for="email" class="label">Email</label>
        <input type="email" class="input" placeholder="Email" name="email" id="email">

        <label for="phone" class="label">Telepon</label>
        <input type="text" class="input" placeholder="No Hp / Telepon" name="phone" id="phone">
        
        <label for="phone" class="label">Alamat</label>
        <textarea class="input p-2" name="address" id="address" placeholder="Alamat lengkap"></textarea>
        
        <input type="hidden" name="event_code" id="event_code" value="<?= $event_code; ?>">
        <button class="button">Daftar</button>
    </form>

    <div class="row justify-content-center mt-5" id="print_container" style="display: none;">
        <div class="col-7 text-center">
            <div class="card" id="printId">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            <img src="" id="text_qrcode" alt="QR Code">
                        </div>
                        <div class="col-12 text-center"><h2 id="text_name"></h2></div>
                        <div class="col-12 text-center" id="text_address"></div>
                        <div class="col-12 text-center" id="text_phone"></div>
                        <div class="col-12 mt-3 text-center fw-900"><h4 id="text_event"></h4></div>
                    </div>
                </div>
            </div>

            <div class="card my-3">
                <div class="card-body">
                    <h3>Preview</h3>
                    <div id="previewImage"></div>
                </div>
            </div>

            <!-- button -->
            <button class="btn btn-primary position-relative" id="print" style="top: -20px;">
                Cetak
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
                </svg>
            </button>
            <input id="btn-Preview-Image" class="btn btn-success position-relative" type="button" value="Preview" style="top: -20px;"/>
            <a class="btn btn-success position-relative" id="btn-Convert-Html2Image" href="#" style="top: -20px;">Download
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                </svg>
            </a>
            <button class="btn btn-secondary position-relative" id="refresh" style="top: -20px;" onclick="window.location.reload()">
                Refresh
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                </svg>
            </button>
        </div>
        <div class="col-6">
            <input type="hidden" name="qrcode" id="value_qrcode" value="">
            <input type="hidden" name="name" id="value_name" value="">
            <input type="hidden" name="address" id="value_address" value="">
            <input type="hidden" name="phone" id="value_phone" value="">
            <input type="hidden" name="event" id="value_event" value="">
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script>
    // submit form
    $('#form-add').on('submit', function(e) {
		e.preventDefault();
		var btn = $('#submit');
		var data_event = $('#InputCode').val();
		// console.log("post data ...");
		$.ajax({
			url: $(this).attr('action'), //nama action script php sobat
			data: new FormData(this),
			// data: data_event,
			type: 'POST',
			dataType: 'json',
			processData: false,
			cache: false,
			contentType: false,
			beforeSend: function() {
				// btn.button('loading');
				btn.html('Loading...');
				// console.log(data);
			},
			complete: function() {
				btn.html('Submit');
			},
			success: function(data) {
				// console.log(data);
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
							// window.location.reload();

                            // console.log(data.data);
                            var hasil = data.data;
                            var img_src = '<?= base_url('assets/img/qrcode/'); ?>' + hasil.qrcode_path;
                            
                            // $('#value_qrcode').val(hasil.qrcode_path);
                            // $('#text_qrcode').attr('src', img_src);
                            // $('#value_name').val(hasil.name);
                            // $('#text_name').text(hasil.name);
                            // $('#value_address').val(hasil.address);
                            // $('#text_address').text(hasil.address);
                            // $('#value_phone').val(hasil.phone);
                            // $('#text_phone').text(hasil.phone);
                            // $('#value_event').val(hasil.event);
                            // $('#text_event').text(hasil.event);

                            // $('#print_container').show();
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

    // print
    function printData()
    {
        var divToPrint=document.getElementById("printId");
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
    $('#print').on('click',function(){ printData(); })

    // convert to image
    var element = $("#html-content-holder"); // global variable
    var getCanvas; // global variable
    $("#btn-Preview-Image").on('click', function () {
         html2canvas(element, {
         onrendered: function (canvas) {
                $("#previewImage").append(canvas);
                getCanvas = canvas;
             }
         });
    });

	$("#btn-Convert-Html2Image").on('click', function () {
    var imgageData = getCanvas.toDataURL("image/png");
    // Now browser starts downloading it instead of just showing it
    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
    $("#btn-Convert-Html2Image").attr("download", "your_pic_name.png").attr("href", newData);
	});
</script>