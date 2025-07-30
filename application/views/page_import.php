<div class="container">
    <div class="row py-3">

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

        <div class="col-12 my-5">
            <h3 class="text-white">Import Data</h3>
            <?php if(!empty($this->session->flashdata('status'))){ ?>
                <div class="alert alert-info" role="alert"><?= $this->session->flashdata('status'); ?></div>
            <?php } ?>

            <form action="<?= base_url('ImportController/import_excel'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Pilih File Excel</label>
                    <input type="file" name="file">
                </div>
                <div>
                    <button class='btn btn-success' type="submit">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        Import		
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-md-offset-3" style="margin-top: 50px;">
            <h3>Daftar Data</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Angkatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 1;
                            foreach ($data as $row) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['jurusan'] ?></td>
                            <td><?= $row['angkatan'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>	
            </div>
        </div>
    </div>
</div>

<script>
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