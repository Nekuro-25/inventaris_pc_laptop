<?php
include "../config/koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM inventaris");

while($data = mysqli_fetch_array($query)){
    echo $data['nama_barang'] . "<br>";
}
?>