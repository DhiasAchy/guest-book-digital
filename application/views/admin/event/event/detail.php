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
			            <table class="table">
        			        <tr>
        			            <td>Nama Event</td>
        			            <td><?= $event['name'] ?></td>
        			        </tr>
        			        <tr>
        			            <td>Tanggal Event</td>
        			            <td><?= date("j F Y", strtotime($event['event_date'])) ?></td>
        			        </tr>
        			        <tr>
        			            <td>Alamat Event</td>
        			            <td><?= $event['event_address'] ?></td>
        			        </tr>
        			    </table>
			        </div>
			        
			    </div>
			</div>
			<div class="col-12 mb-3">
			    <div class="card shadow mb-2">
			        <div class="card-header">
			            Peserta Event
			            <div class="float-right">
						    <a class="btn btn-primary border-0" href="<?= base_url(array_slice(explode('/', uri_string()), 0, -3)) . '/guest/add/'.$event['id']; ?>" role="button"><i class="mdi mdi-account-multiple-plus"></i> Tambah
                                <div class="ripple-container"></div>
                            </a>
						</div>
			        </div>
			        <div class="card-body">
			            <table id="datatable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">Alamat</th>
                                    <th scope="col" class="text-center">Waktu Hadir</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table_row">
                                <?php $no = 0;
                                    foreach ($guest as $key => $value) { $no++; ?>
                                        <tr>
                                            <td class="text-center"><?= $no; ?></td>
                                            <td class="text-center"><?= $value['name']; ?></td>
                                            <td class="text-center"><?= $value['address']; ?></td>
                                            <td class="text-center"><?= ($value['event_attendance'] == '') ? "-" : $value['event_attendace']; ?></td>
                                            <td class="text=center">
                                                <a href="<?= base_url('admin/event/event/edit/' . $value['id'] . ''); ?>" class="btn btn-primary border-0"><i class="fas fa-edit"></i>
                                                    <div class="ripple-container"></div>
                                                </a>
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
			</div>
		</div>
	</div>
</div>
<!-- End Content -->

<script>
	$(document).ready(function() {
		
	});
</script>