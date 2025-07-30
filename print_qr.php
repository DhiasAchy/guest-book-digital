<?php 
    // get data from url
    if (!isset($_GET['event']) || !isset($_GET['name']) || !isset($_GET['address']) || !isset($_GET['phone']) || !isset($_GET['qrcode'])) {
        die("Invalid parameters");
    } else {
        $event = $_GET['event'];
        $name = $_GET['name'];
        $address = $_GET['address'];
        $phone = $_GET['phone'];
        $qrcode = $_GET['qrcode'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Qrcode - Guest Book Digital</title>

    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
        }

        .card {
            background-color: white;
            width: 350px;
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            margin-bottom: 30px;
        }

        .qrcode img { width: 150px; height: 150px; }

        .info h2 {
            margin: 10px 0 5px;
            font-size: 1.5em;
            color: #222;
        }
        .info p {
            margin: 4px 0;
            font-size: 0.95em;
            color: #444;
        }
        .info i {
            font-size: 0.70em;
            color: #666;
            display: block;
            margin-top: 30px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .button {
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            /* background-color: #4CAF50; */
            background-color: #198754;
            color: white;
            transition: background 0.2s;
        }
        a { text-decoration: none; }

        .button:hover { background-color: #45a049; }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .buttons { display: none !important; }
            .card {
                box-shadow: none;
                margin: 0;
                page-break-inside: avoid;
            }
            #html-content-holder { display: none !important; }
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
</head>
<body>
    <div id="html-content-holder2">
        <div class="card" id="card">
            <div class="info">
                <h1><?= $event; ?></h1>
            </div>
            <div class="qrcode">
                <img id="qrImage" src="<?= $qrcode; ?>" alt="QR Code">
            </div>
            <div class="info">
                <h2><?= $name; ?></h2>
                <p><?= $address; ?></p>
                <p><?= $phone; ?></p>
            </div>
            <div class="info note">
                <i>*Catatan : Scan qrcode berikut saat menghadiri acara.</i>
            </div>
        </div>
    </div>

    <div id="html-content-holder" style="background-color: #F0F0F1; color: #00cc65; width: 500px;
        padding-left: 25px; padding-top: 10px;">
        <strong>Sekilas.info</strong><hr/>
        <h3 style="color: #3e4b51;">
            QRCode
        </h3>
        <p style="color: #3e4b51;">
            <b>QRCode</b> berikut berfungsi sebagai identitas anda saat menghadiri acara yang diadakan, dengan men scan qrcode berikut yang akan dibantu oleh petugas saat anda mendatangi acara maka bisa sebagai konfirmasi bahwa ini benar anda, dan memudahkan anda untuk tidak perlu lagi menulis nama alamat dll pada buku tamu.
        </p>
        <p style="color: #3e4b51;">Untuk mendownload sebagai gambar, silahkan klik preview dulu baru klik download.</p>

        <p style="color: #3e4b51;">
            <b>Penting</b> : Silahkan download <b>( jangan lupa disimpan )</b> atau cetak QRCode ini untuk dibawa ke acara.
    </div>

    <div class="buttons">
        <input id="btn-Preview-Image" type="button" value="Preview"/>
        <a id="btn-Convert-Html2Image" class="button" href="#">Download</a>
        <button class="button" onclick="printCard()">Cetak</button>
    </div>
    <br/>

    <div id="html-content-holder">
        <h3>Preview :</h3>
        <div id="previewImage"></div>
    </div>
    

    <script>
        function downloadCard() {
            const card = document.getElementById('card');
            html2canvas(card).then(canvas => {
                const link = document.createElement('a');
                link.download = 'kartu-qrcode.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
        function printCard() {
            const qrImage = document.getElementById('qrImage');
            if (!qrImage.complete) {
                qrImage.onload = () => window.print();
            } else { window.print(); }
        }

        $(document).ready(function() {
            var element = $("#html-content-holder2"); // global variable
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
                var imagename = '<?= $name; ?>';
                var imgageData = getCanvas.toDataURL("image/png");
                // Now browser starts downloading it instead of just showing it
                var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
                $("#btn-Convert-Html2Image").attr("download", imagename+".png").attr("href", newData);
            });
        });
    </script>
</body>
</html>