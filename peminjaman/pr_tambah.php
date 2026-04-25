<?php

include("../config/auth.php");
include("../config/koneksi.php");
adminOrTeknisi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: index.php?error=invalid_request");
    exit;
}

if (empty($_POST['id_barang']) || empty($_POST['nama_peminjam']) || empty($_POST['tanggal_pinjam'])) {
    header("Location: tambah.php?error=data_tidak_lengkap");
    exit;
}

$id_barang     = (int) $_POST['id_barang'];
$nama_peminjam = trim($_POST['nama_peminjam']);
$tanggal_pinjam = trim($_POST['tanggal_pinjam']);

// ✅ FIX BUG #2: Validasi format tanggal
$tgl = DateTime::createFromFormat('Y-m-d', $tanggal_pinjam);
if (!$tgl || $tgl->format('Y-m-d') !== $tanggal_pinjam) {
    header("Location: tambah.php?error=format_tanggal_salah");
    exit;
}

// ✅ FIX BUG #1: Gunakan transaksi database
mysqli_begin_transaction($koneksi);

try {
    // Kunci dan cek status barang dalam satu query (FOR UPDATE mencegah race condition)
    $stmtCek = mysqli_prepare($koneksi, "
        SELECT status FROM inventaris
        WHERE id_barang = ? AND deleted_at IS NULL
        LIMIT 1 FOR UPDATE
    ");
    mysqli_stmt_bind_param($stmtCek, "i", $id_barang);
    mysqli_stmt_execute($stmtCek);
    $resultCek = mysqli_stmt_get_result($stmtCek);
    $barang = mysqli_fetch_assoc($resultCek);
    mysqli_stmt_close($stmtCek);

    if (!$barang) {
        throw new Exception("barang_tidak_ditemukan");
    }
    if ($barang['status'] === 'dipinjam') {
        throw new Exception("barang_sedang_dipinjam");
    }

    // Insert peminjaman
    $stmtInsert = mysqli_prepare($koneksi, "
        INSERT INTO peminjaman (id_barang, nama_peminjam, tanggal_pinjam, status)
        VALUES (?, ?, ?, 'dipinjam')
    ");
    mysqli_stmt_bind_param($stmtInsert, "iss", $id_barang, $nama_peminjam, $tanggal_pinjam);
    mysqli_stmt_execute($stmtInsert);
    mysqli_stmt_close($stmtInsert);

    // Update status barang
    $stmtUpdate = mysqli_prepare($koneksi, "UPDATE inventaris SET status = 'dipinjam' WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtUpdate, "i", $id_barang);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    mysqli_commit($koneksi);
    header("Location: index.php?pesan=berhasil");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $pesan = $e->getMessage();
    if (in_array($pesan, ['barang_tidak_ditemukan', 'barang_sedang_dipinjam'])) {
        header("Location: tambah.php?error=$pesan");
    } else {
        header("Location: tambah.php?error=gagal_simpan");
    }
    exit;
}
?>