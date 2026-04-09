<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Sistem Inventaris</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="login-container">

    <div class="login-box">

        <h2>Sistem Inventaris</h2>
        <p>PC & Laptop</p>

        <!-- PESAN ERROR -->
        <?php if(isset($_GET['error'])){ ?>
            <div style="color:red; margin-bottom:10px;">
                Username atau password salah!
            </div>
        <?php } ?>

        <form action="login/proses_login.php" method="POST" autocomplete="off">

            <div class="input-group">
                <label>Username</label>
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Masukkan Username" 
                    required
                    minlength="3"
                >
            </div>

            <div class="input-group">
                <label>Password</label>
                <div style="display:flex; gap:10px;">
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Masukkan Password" 
                        required
                        minlength="6"
                    >
                    <button type="button" onclick="togglePassword()">Lihat</button>
                </div>
            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>

    </div>

</div>

<script>
function togglePassword() {
    var password = document.getElementById("password");
    password.type = (password.type === "password") ? "text" : "password";
}
</script>

</body>
</html>