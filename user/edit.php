<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../index.php");
    exit;
}

include("../config/koneksi.php");

$id = $_GET['id'];

$query = mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$id'");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    mysqli_query($koneksi,"
    UPDATE user SET
    nama='$nama',
    username='$username',
    password='$password',
    role='$role'
    WHERE id_user='$id'
    ");

    header("Location: data_user.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User</title>

<link rel="stylesheet" href="../css/dashboard.css">

</head>
<body>

<div class="container">

<!-- Sidebar -->
<div class="sidebar">

<h2>Inventaris</h2>

<ul>
<li><a href="../dashboard/index.php">Dashboard</a></li>
<li><a href="../inventaris/data.php">Data Inventaris</a></li>
<li><a href="../lokasi/lokasi.php">Data Lokasi</a></li>
<li><a href="../perbaikan/data_perbaikan.php">Perbaikan</a></li>
<li><a href="../laporan/laporan.php">Laporan</a></li>
<li><a href="data_user.php">Manajemen User</a></li>
<li><a href="../logout.php">Logout</a></li>
</ul>

</div>

<!-- Main Content -->
<div class="main">

<div class="topbar">
<h1>Edit User</h1>
</div>

<div class="form-container">

<form method="POST">

<div class="form-group">
<label>Nama</label>
<input type="text" name="nama" value="<?php echo $data['nama']; ?>" required>
</div>

<div class="form-group">
<label>Username</label>
<input type="text" name="username" value="<?php echo $data['username']; ?>" required>
</div>

<div class="form-group">
<label>Password</label>

<div style="display:flex; gap:10px;">
<input type="password" id="password" name="password" value="<?php echo $data['password']; ?>" required>

<button type="button" onclick="togglePassword()">Lihat</button>
</div>

</div>

<div class="form-group">
<label>Role</label>

<select name="role">

<option value="Admin" <?php if($data['role']=="Admin") echo "selected"; ?>>Admin</option>

<option value="Teknisi" <?php if($data['role']=="Teknisi") echo "selected"; ?>>Teknisi</option>

<option value="User" <?php if($data['role']=="User") echo "selected"; ?>>User</option>

</select>

</div>

<div class="form-buttons">

<button type="submit" name="update" class="btn-simpan">Update</button>

<a href="data_user.php" class="btn-batal">Batal</a>

</div>

</form>

</div>

</div>

</div>

<script>

function togglePassword(){

var password = document.getElementById("password");

if(password.type === "password"){
password.type = "text";
}else{
password.type = "password";
}

}

</script>

</body>
</html>