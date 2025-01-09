<div class="container">
    <div class="row py-3">
        
        <!-- swiper card -->
        <div class="col-12 mt-3 mb-2 d-none">
            <!-- Slider main container -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">Slide 1</div>
                    <div class="swiper-slide">Slide 2</div>
                    <div class="swiper-slide">Slide 3</div>
                    <div class="swiper-slide">Slide 4</div>
                    <div class="swiper-slide">Slide 5</div>
                    <div class="swiper-slide">Slide 6</div>
                    <div class="swiper-slide">Slide 7</div>
                    <div class="swiper-slide">Slide 8</div>
                    <div class="swiper-slide">Slide 9</div>
                </div>
            </div>
        </div>
        
        <!-- swiper parallax -->
        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper1">
            <!--<div class="parallax-bg" style="background-image: url(https://swiperjs.com/demos/images/nature-1.jpg);" data-swiper-parallax="-23%"></div>-->
            <div class="parallax-bg" style="background-image: url(assets/images/Thumbnail-Example.webp);" data-swiper-parallax="-23%"></div>
            <div class="swiper-wrapper thumbnail-event">
                <div class="swiper-slide">
                    <div class="title" data-swiper-parallax="-300">Buku Tamu Digital</div>
                    <div class="subtitle" data-swiper-parallax="-200">Perkenalan</div>
                    <div class="text" data-swiper-parallax="-100">
                        <p class="mb-5">Ini adalah buku tamu digital. Anda tidak perlu repot lagi menuliskan nama dan tanda tangan pada buku kehadiran, cukup perlihatkan Qr untuk di scan. Maka anda sudah tercatat hadir lengkap dengan data diri anda</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="title" data-swiper-parallax="-300">Buku Tamu Digital</div>
                    <div class="subtitle" data-swiper-parallax="-200">Mulai</div>
                    <div class="text" data-swiper-parallax="-100">
                        <span class="text-black">Untuk memulai scan, silahkan masukkan nama Event anda.</span>
                        <div class="input-scan">
                            <div class="mb-3 mt-2">
                                <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)) . 'Homepage/goto_event'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
                                    <input type="text" class="form-control" name="event" value="" id="InputCode" placeholder="Masukkan Kode Event">
                                    <button type="submit" id="submit" class="btn btn-success btn-raised mb-0 mt-3">Cari</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide d-none">
                    <div class="content">
                    <h1>Buku Tamu Digital</h1>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p class="mb-5">Ini adalah buku tamu digital. Anda tidak perlu repot lagi menuliskan nama dan tanda tangan pada buku kehadiran, cukup perlihatkan Qr untuk di scan. Maka anda sudah tercatat hadir lengkap dengan data diri anda</p>
                            
                            <span class="">Untuk memulai scan, silahkan masukkan nama Event anda.</span>
                            <div class="input-scan">
                                <div class="mb-3 mt-2">
                                    <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)) . 'Homepage/goto_event'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
                                        <input type="text" class="form-control" name="event" value="" id="InputCode1" placeholder="Masukkan Kode Event">
                                        <button type="submit" id="submit" class="btn btn-success btn-raised mb-0 mt-3">Cari</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
        
        <!-- thumbnail -->
        <div class="col-12 mt-3 mb-2 d-none">
            <div class="thumbnail-event">
                <img src="assets/images/Thumbnail-Example.webp" class="img-fluid rounded" alt="Notebook" style="width:100%;">
                <div class="content">
                    <h1>Buku Tamu Digital</h1>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p class="mb-5">Ini adalah buku tamu digital. Anda tidak perlu repot lagi menuliskan nama dan tanda tangan pada buku kehadiran, cukup perlihatkan Qr untuk di scan. Maka anda sudah tercatat hadir lengkap dengan data diri anda</p>
                            
                            <span class="">Untuk memulai scan, silahkan masukkan nama Event anda.</span>
                            <div class="input-scan">
                                <div class="mb-3 mt-2">
                                    <form class="mb-0 col-12" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -1)) . 'Homepage/goto_event'; ?>" id="form-add" method="POST" enctype="multipart/form-data">
                                        <input type="text" class="form-control" name="event" value="" id="InputCode1" placeholder="Masukkan Kode Event">
                                        <button type="submit" id="submit" class="btn btn-success btn-raised mb-0 mt-3">Cari</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            effect: "cards",
            grabCursor: true,
        });
        
        var swiper = new Swiper(".mySwiper1", {
            speed: 600,
            parallax: true,
            pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    </script>

<script>
    $('#datatable').dataTable({
        "pageLength": 10
    });
    
    $('#form-add').on('submit', function(e) {
		e.preventDefault();
		var btn = $('#submit');
		var data_event = $('#InputCode').val();
		console.log("post data ...");
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
				console.log(data_event);
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
</script>

<!-- untuk buka halaman scan, simpan event id nya, untuk validasi ketika scan kemudian submit, cocokan event id dengan user id apakah ada, untuk validasi hadir dan bukan tamu undangan -->

