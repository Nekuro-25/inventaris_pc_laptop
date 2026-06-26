<?php

include("../config/auth.php");
include("../config/koneksi.php");

$query = mysqli_query($koneksi, "
SELECT * FROM inventaris 
WHERE deleted_at IS NULL
");

while($data = mysqli_fetch_array($query)){
    echo $data['nama_barang'] . "<br>";
}
?>