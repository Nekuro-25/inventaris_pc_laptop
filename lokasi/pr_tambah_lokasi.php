/* ✅ FIX SQL Injection: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "INSERT INTO lokasi (nama_lokasi) VALUES (?)");
if (!$stmt) {
    header("Location: tambah_lokasi.php?error=gagal_simpan");
    exit;
}
mysqli_stmt_bind_param($stmt, "s", $nama_lokasi);
