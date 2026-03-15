<?php

include "../config/koneksi.php";

if(isset($_POST['simpan'])){

    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jenis = $_POST['jenis'];
    $merk = $_POST['merk'];
    $processor = $_POST['processor'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $id_lokasi = $_POST['lokasi'];
    $status = $_POST['status'];

    $query = mysqli_query($koneksi,"INSERT INTO inventaris (kode_barang,nama_barang,jenis,merk,processor,ram,storage,id_lokasi,status) VALUES ('$kode_barang','$nama_barang','$jenis','$merk','$processor','$ram','$storage','$id_lokasi','$status')
    ");

    if($query){
        header("Location: data.php");
    }else{
        echo "Data gagal disimpan : " . mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Inventaris</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Inventaris</h2>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="data.php">Data Inventaris</a></li>
            <li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
            <li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
            <li><a href="../laporan/laporan.php">Laporan</a></li>
            <li><a href="../user/data_user.php">Manajemen User</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>


    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h1>Tambah Inventaris</h1>
        </div>


        <div class="form-container">

            <form method="POST">

                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="kode_barang" placeholder="Masukkan kode barang" required>
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" placeholder="Masukkan nama barang">
                </div>

                <div class="form-group">
                    <label>Jenis Barang</label>
                    <select name="jenis">
                        <option value="PC">PC</option>
                        <option value="Laptop">Laptop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" placeholder="Masukkan merk">
                </div>

                <div class="form-group">
                    <label>Processor</label>
                    <input type="text" name="processor" placeholder="Masukkan processor">
                </div>

                <div class="form-group">
                    <label>RAM</label>
                    <input type="text" name="ram" placeholder="Masukkan RAM">
                </div>

                <div class="form-group">
                    <label>Storage</label>
                    <input type="text" name="storage" placeholder="Masukkan storage">
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Masukkan lokasi">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="aktif">Aktif</option>
                        <option value="rusak">Rusak</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>

                <div class="form-buttons">

                    <button class="btn-simpan" type="submit" name="simpan">Simpan</button>
                    <a href="data.php" class="btn-batal">Batal</a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>