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

        <form action="login/proses_login.php" method="POST">

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>

    </div>

</div>

</body>
</html>