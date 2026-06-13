<?php
session_start();

/* Membuat CSRF token jika belum ada di session untuk mencegah request palsu dari luar */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Sistem Inventaris</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<main class="login-container">

    <section class="login-box">

        <header>
            <h2>Sistem Inventaris</h2>
            <p>PC & Laptop</p>
        </header>

        <!-- PESAN ERROR -->
        <?php
        /* Mengambil parameter error dari URL untuk menampilkan pesan sesuai validasi backend */
        $error = $_GET['error'] ?? null;

        if ($error === 'empty') {
            echo '<div class="error-message">Username dan password tidak boleh kosong!</div>';
        } elseif ($error === 'format') {
            echo '<div class="error-message">Format username atau password tidak valid!</div>';
        } elseif ($error === 'invalid') {
            echo '<div class="error-message">Username atau password salah!</div>';
        }
        ?>

        <form action="login/proses_login.php" method="POST" autocomplete="off">

            <!-- CSRF TOKEN untuk validasi request di server agar tidak bisa dipalsukan -->
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

            <div class="input-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    placeholder="Masukkan Username" 
                    required
                    minlength="3"
                    autocomplete="username"
                >
            </div>

            <div class="input-group">
                <label for="password">Password</label>

                <div class="password-wrapper">
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Masukkan Password" 
                        required
                        minlength="6"
                        autocomplete="current-password"
                    >

                    <button type="button" onclick="togglePassword(this)">
                        Lihat
                    </button>
                </div>

            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>

    </section>

</main>

<script>
/* Toggle untuk melihat dan menyembunyikan password agar user lebih mudah cek input */
function togglePassword(btn) {
    const password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
        btn.textContent = "Sembunyikan";
    } else {
        password.type = "password";
        btn.textContent = "Lihat";
    }
}
</script>

</body>
</html>