/* ✅ FIX SQL Injection: Prepared Statement */
$stmt = mysqli_prepare($koneksi, "
    UPDATE lokasi SET nama_lokasi = ?
    WHERE id_lokasi = ? AND deleted_at IS NULL
");
if (!$stmt) {
    header("Location: lokasi.php?error=gagal_update");
    exit;
}
mysqli_stmt_bind_param($stmt, "si", $nama_lokasi, $id);
