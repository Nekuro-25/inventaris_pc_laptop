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

// ambil data inventaris (FIX: tambah soft delete)
$query = mysqli_query($koneksi,"
SELECT inventaris.*, lokasi.nama_lokasi 
FROM inventaris
JOIN lokasi ON inventaris.id_lokasi = lokasi.id_lokasi
WHERE inventaris.deleted_at IS NULL
AND lokasi.deleted_at IS NULL
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

    body{
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #2f2f2f;
    }
    
    .header{
        text-align:center;
        padding:15px;
        border:1px solid #e6e0d6;
        background:#faf8f4;
    }
    
    .header h2{
        margin:0 0 5px 0;
        color:#557a60;
    }
    
    .header p{
        margin:0;
        color:#7a7a7a;
    }
    
    hr{
        border:none;
        border-top:1px solid #e6e0d6;
        margin:20px 0;
    }
    
    table{
        width:100%;
        border-collapse:collapse;
    }
    
    th{
        background:#faf8f4;
        color:#2f2f2f;
        border:1px solid #e6e0d6;
        padding:8px;
    }
    
    td{
    border:1px solid #e6e0d6;
    padding:8px;
    }

    .no{
        text-align:center;
        width:40px;
    }

    .center{
        text-align:center;
    }

    .badge{
        padding:3px 8px;
        border-radius:20px;
        font-size:11px;
    }

    .tersedia{
        background:#dfe9dd;
        color:#567052;
    }

    .dipinjam{
        background:#eee3c9;
        color:#80652d;
    }

    .rusak{
        background:#f1d9d4;
        color:#944f42;
    }

    .footer{
        margin-top:40px;
        text-align:right;
    }

    .footer p{
        margin:0;
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
            <td><?= htmlspecialchars($row['kode_barang']) ?></td>
            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
            <td><?= htmlspecialchars($row['jenis']) ?></td>
            <td><?= htmlspecialchars($row['merk']) ?></td>
            <td><?= htmlspecialchars($row['nama_lokasi']) ?></td>
            <td class="center">
                <?php
                    $status = strtolower($row['status']);
                    if($status == 'tersedia'){
                        echo '<span class="badge tersedia">Tersedia</span>';
                    }
                    elseif($status == 'dipinjam'){
                        echo '<span class="badge dipinjam">Dipinjam</span>';
                    }
                    elseif($status == 'rusak'){
                        echo '<span class="badge rusak">Rusak</span>';
                    }
                    else{
                        echo htmlspecialchars($row['status']);
                    }
                ?>
            </td>
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

// tampilkan di browser
$dompdf->stream("laporan_inventaris.pdf", array("Attachment" => false));
?>