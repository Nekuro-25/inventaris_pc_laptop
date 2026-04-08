<?php

include("../config/auth.php");
include("../config/koneksi.php");

// hanya admin & teknisi yang boleh cetak
if(!$isAdmin && !$isTeknisi){
    die("Akses ditolak");
}

// panggil composer
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

// ambil data inventaris
$query = mysqli_query($koneksi,"
SELECT inventaris.*, lokasi.nama_lokasi 
FROM inventaris
JOIN lokasi ON inventaris.id_lokasi = lokasi.id_lokasi
");

$data = [];
while($row = mysqli_fetch_assoc($query)){
    $data[] = $row;
}

// mulai tampung HTML
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .header p {
            margin-top: 0;
            font-size: 12px;
        }

        hr {
            margin: 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #f2f2f2;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>

</head>
<body>

<div class="header">
    <h2>LAPORAN INVENTARIS</h2>
    <p>Tanggal Cetak: <?= date('d-m-Y') ?></p>
</div>

<hr>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jenis</th>
            <th>Merk</th>
            <th>Lokasi</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
    <?php 
    $no = 1;
    foreach($data as $row){
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['kode_barang'] ?></td>
            <td><?= $row['nama_barang'] ?></td>
            <td><?= $row['jenis'] ?></td>
            <td><?= $row['merk'] ?></td>
            <td><?= $row['nama_lokasi'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div class="footer">
    <p>Mengetahui,</p>
    <br><br><br>
    <p>(___________________)</p>
</div>

</body>
</html>

<?php
$html = ob_get_clean();

// inisialisasi dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// setting kertas
$dompdf->setPaper('A4', 'portrait');

// render
$dompdf->render();

// tampilkan di browser (preview)
$dompdf->stream("laporan_inventaris.pdf", array("Attachment" => false));