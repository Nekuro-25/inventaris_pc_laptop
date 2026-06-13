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
        <?php if(isset($_GET['error'])){ ?>
            <div class="error-message">
                Username atau password salah!
            </div>
        <?php } ?>

        <form action="login/proses_login.php" method="POST" autocomplete="off">

            <div class="input-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    placeholder="Masukkan Username" 
                    required
                    minlength="3"
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
                    >

                    <button type="button" onclick="togglePassword()">
                        Lihat
                    </button>
                </div>

            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>

    </section>

</main>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    password.type = password.type === "password" ? "text" : "password";
}
</script>

</body>
</html>