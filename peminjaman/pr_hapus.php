<?php

include("../config/auth.php");
include("../config/koneksi.php");

$id = $_GET['id'];

/* ambil data dulu */
$data = mysqli_query($koneksi,"
SELECT * FROM peminjaman WHERE id='$id'
");

$row = mysqli_fetch_assoc($data);

/* kalau masih dipinjam, kembalikan status barang */
if($row['status'] == 'dipinjam'){
    mysqli_query($koneksi,"
    UPDATE inventaris 
    SET status='aktif' 
    WHERE id_barang='".$row['id_barang']."'
    ");
}

/* soft delete */
$query = mysqli_query($koneksi,"
UPDATE peminjaman 
SET deleted_at = NOW() 
WHERE id='$id'
");

if($query){

    header("Location: index.php");
    exit;

}else{

    echo "Gagal menghapus data: " . mysqli_error($koneksi);

}

?>