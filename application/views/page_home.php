<div class="container">
    <div class="row py-3">
        
        <!-- thumbnail -->
        <div class="col-12 mt-3 mb-2">
            <div class="thumbnail-event">
                <img src="<?= base_url($event['image']) ?>" class="img-fluid rounded" alt="Notebook" style="width:100%;">
                <div class="content">
                    <h1><?= $event['name'] ?></h1>
                    <p><?= date("j F Y", strtotime($event['event_date'])) ?></p>
                    <p><?= $event['event_address']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-12 my-3">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-secondary elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Tamu / Hadir</span>
                            <span class="info-box-number"><span class="fs-5"><?= $guest_query['guest_event'] ?></span> / <?= $guest_query['guest_total'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-primary elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hadir</span>
                            <span class="info-box-number"><?= $guest_query['guest_datang'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-warning elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Belum</span>
                            <span class="info-box-number"><?= $guest_query['guest_event']-$guest_query['guest_datang'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-danger elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tidak Terdaftar</span>
                            <span class="info-box-number"><?= $guest_query['guest_salah'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4 my-3">
            <div class="card shadow container-scan-area text-white rounded">
                <div class="card-body text-center">
                    <!--<p>Scan</p>-->
                    <div class="qr-logo d-none">
                        <img src="assets/images/qr image.png" class="img-fluid" alt="">
                    </div>
                    
                    <div class="input-scan">
                        <div class="mb-3">
                            <!--<form class="" action="<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/check_in'; ?>" id="form-add" method="POST" enctype="multipart/form-data">-->
                                <label for="exampleInput" class="form-label">Input Scan</label>
                                <input type="text" class="form-control" id="InputId" placeholder="Input Form" onchange="check_in()">
                            <!--</form>-->
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-success mb-2">Success</button>
                    <p class="scan-tutorial-text">Untuk scan QR Code <br> pastikan cursor berada dalam form input agar dapat langsung menulis hasil QR Code.</p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-8 my-3">
            <div class="row">
                <!-- summary -->
                <div class="col-12 col-sm-6 d-none">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-secondary elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Tamu</span>
                            <span class="info-box-number"><?= $guest_query['guest_total'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 d-none">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-primary elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hadir</span>
                            <span class="info-box-number"><?= $guest_query['guest_event'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 d-none">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-danger elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Belum</span>
                            <span class="info-box-number"><?= $guest_query['guest_total'] - $guest_query['guest_datang'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 d-none">
                    <div class="info-box mb-3 bg-dark">
                        <span class="info-box-icon bg-warning elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="#ffffff" class="bi bi-pie-chart-fill" viewBox="0 0 16 16">
                                <path d="M15.985 8.5H8.207l-5.5 5.5a8 8 0 0 0 13.277-5.5zM2 13.292A8 8 0 0 1 7.5.015v7.778zM8.5.015V7.5h7.485A8 8 0 0 0 8.5.015"/>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tidak Terdaftar</span>
                            <span class="info-box-number"><?= $guest_query['guest_salah'] ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- guest list -->
                <div class="col-12 mb-2">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4>Daftar Tamu Datang</h4>
                            <table id="datatable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col" class="text-center">Nama</th>
                                        <th scope="col" class="text-center">Alamat</th>
                                        <th scope="col" class="text-center">Waktu Hadir</th>
                                    </tr>
                                </thead>
                                <tbody id="table_row">
                                    <?php $no = 0;
                                        foreach ($guest as $key => $value) { $no++; ?>
                                            <tr>
                                                <td class="text-center"><?= $no; ?></td>
                                                <td class="text-center"><?= $value['guest_name']; ?></td>
                                                <td class="text-center"><?= $value['guest_address']; ?></td>
                                                <td class="text-center" data-order="<?= strtotime($value['created_at']) ?>"><?= ($value['created_at'] == '') ? "-" : date("j F Y, H:i", strtotime($value['created_at'])); ?></td>
                                            </tr>
                                            <?php
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
</div>

<script>
    $('#datatable').dataTable({
        "pageLength": 10
    });
    
    function check_in() {
        var qr_id = $('#InputId').val();
        var event_id = <?= $event['id']; ?>;
        $.post( "<?= base_url(array_slice(explode('/', uri_string()), 0, -2)) . '/check_in'; ?>", {
			qr: qr_id,
			event_id: event_id
		}, function(data,status){
			if ( !data.error ) {
			    let timerInterval;
                swal({
                    title: "Success!",
                    // html: data.msg+", Auto close in <b></b> milliseconds.",
                    text: data.msg,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log("I was closed by the timer");
                        location.reload();
                    }
                });
			    
			    /*swal("Success!", data.msg, "success")
				.then((value) => {
					location.reload();
				});*/
			} else {
				swal("Oops...", data.msg, "error");
			}
		});
    }
</script>

<!-- untuk buka halaman scan, simpan event id nya, untuk validasi ketika scan kemudian submit, cocokan event id dengan user id apakah ada, untuk validasi hadir dan bukan tamu undangan -->

