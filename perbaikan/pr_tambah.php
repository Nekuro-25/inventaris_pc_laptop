<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();
blockUser();

/* VALIDASI METHOD */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: data_perbaikan.php");
    exit;
}

/* VALIDASI INPUT */
if(
    empty($_POST['id_barang']) ||
    empty($_POST['tanggal']) ||
    empty($_POST['kerusakan'])
){
    echo "Data tidak lengkap!";
    exit;
}

/* AMANKAN INPUT */
$id_barang = (int) $_POST['id_barang'];
$tanggal = trim($_POST['tanggal']);
$kerusakan = trim($_POST['kerusakan']);
$tindakan = isset($_POST['tindakan']) ? trim($_POST['tindakan']) : null;

/* VALIDASI BARANG ADA */
$stmt = mysqli_prepare($koneksi, "
    SELECT id_barang FROM inventaris 
    WHERE id_barang = ? AND deleted_at IS NULL
");
mysqli_stmt_bind_param($stmt, "i", $id_barang);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){
    echo "Barang tidak ditemukan!";
    exit;
}

/* SIMPAN DATA PERBAIKAN */
$stmtInsert = mysqli_prepare($koneksi, "
    INSERT INTO perbaikan (id_barang, tanggal, kerusakan, tindakan) 
    VALUES (?, ?, ?, ?)
");
mysqli_stmt_bind_param($stmtInsert, "isss", $id_barang, $tanggal, $kerusakan, $tindakan);

if(mysqli_stmt_execute($stmtInsert)){

    /* UPDATE STATUS INVENTARIS */
    $stmtUpdate = mysqli_prepare($koneksi, "
        UPDATE inventaris 
        SET status = 'rusak' 
        WHERE id_barang = ?
    ");
    mysqli_stmt_bind_param($stmtUpdate, "i", $id_barang);
    
    if(!mysqli_stmt_execute($stmtUpdate)){
        die("Gagal update status inventaris: " . mysqli_error($koneksi));
    }

    header("Location: data_perbaikan.php?pesan=berhasil");
    exit;

}else{

    echo "Data gagal disimpan : " . mysqli_error($koneksi);

}

?>