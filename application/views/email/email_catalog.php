<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body {
			padding: 30px 5px;
			width: 100%;
			height: 100%;
			font-family: 'Roboto', sans-serif;
			background-color: #f8fbfe;
			margin: 0;
		}

		.container {
			max-width: 768px;
			margin: 0 auto;
			text-align: center;
		}

		.main-color {
			color: #fac839;
		}

		.blue-color {
			color: #1898f3;
		}

		.black {
			color: #000000;
		}

		p {
			margin: 10px 0;
		}

		img.email-picture {
			width: 100%;
			max-width: 400px;
			object-fit: contain;
			margin-bottom: 20px;
		}

		.judul {
			word-wrap: break-word;
			font-size: 34px;
			line-height: 1.15;
			letter-spacing: -.015em;
			color: #010101;
			font-weight: 700;
		}

		.deskripsi {
			word-wrap: break-word;
			font-size: 16px;
			line-height: 24px;
			letter-spacing: 1px;
			font-weight: 400;
			color: rgba(0, 0, 0, 0.54);
			/*color: #484848 ;*/
		}

		.tombol {
			margin: 20px auto;
			text-align: center;
			width: 90%;
			max-width: 480px;
		}

		a.link {
			font-size: 16px;
			line-height: 24px;
			letter-spacing: 1px;
			font-weight: 400;
			color: #1898f3;
		}

		a.button {
			cursor: pointer;
			background: #010101;
			border-radius: 30px;
			border: 0;
			color: #fff;
			position: relative;
			display: block;
			text-decoration: none;
			font-size: 18px;
			font-weight: 300;
			line-height: 36px;
			letter-spacing: 0.2px;
			padding: 15px 25px;
			-webkit-transition: all 0.5s;
			-moz-transition: all 0.5s;
			transition: all 0.5s;
			outline: none;
		}
	</style>
</head>

<body>

	<div class="container">
		<!-- <img src="<?= $img ?>" alt="" class="email-picture"> -->
		<div class="judul">
			<p class="main-color">Halo, <?= $name ?></p>
		</div>
		<div class="deskripsi">
			<p>
				Berikut adalah katalog dari brand <strong><?= $brand ?></strong>
			</p>
		</div>
		<div class="tombol">
			<a href="<?= $link ?>" class="button">
				Download
			</a>
			<br>
			<p>
				or click link : <br>
				<a class="link" href="<?= $link ?>">
					<?= $link ?>
				</a>
			</p>
		</div>
	</div>

</body>

</html>