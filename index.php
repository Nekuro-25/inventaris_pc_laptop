<?php

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/constants.php';

// ... lanjutkan kode seperti biasa ...
// --- Flash Message ---
function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
    } elseif (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

// --- CSRF Token ---
function generateCSRFToken() {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

$csrf_token = generateCSRFToken();
$error = flash('login_error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Inventaris</title>
    <!-- Gunakan BASE_PATH untuk CSS -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>style.css">
</head>
<body>
    <main class="login-container">
        <section class="login-box">
            <header>
                <h2>Sistem Inventaris</h2>
                <p>PC & Laptop</p>
            </header>

            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="<?= BASE_PATH ?>login/proses_login.php" method="POST">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Masukkan Username" required minlength="3"
                           autocomplete="username">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" 
                               placeholder="Masukkan Password" required minlength="6"
                               autocomplete="current-password">
                        <button type="button" id="togglePassword" aria-label="Tampilkan password">
                            Lihat
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </section>
    </main>

    <script>
        (function() {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.textContent = isPassword ? 'Sembunyikan' : 'Lihat';
                this.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
            });
        })();
    </script>
</body>
</html>
