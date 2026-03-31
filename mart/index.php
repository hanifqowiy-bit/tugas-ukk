<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($q);

    if ($data && password_verify($password, $data['password'])) {

        $_SESSION['user_id'] = $data['id'];
        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
*{
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    margin:0;
    background:#1e9be2;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* CARD */
.box{
    width:400px;
    background:white;
    padding:35px 30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,.25);
}

/* TITLE */
.box h2{
    text-align:center;
    margin-bottom:25px;
}

/* LABEL */
label{
    font-size:13px;
    color:#333;
}

/* INPUT */
input{
    width:100%;
    padding:11px;
    margin:5px 0 15px;
    border-radius:8px;
    border:1px solid #ccc;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    background:#1e9be2;
    border:none;
    color:white;
    border-radius:8px;
    font-size:14px;
    cursor:pointer;
}

button:hover{
    background:#1682bf;
}

/* TEXT */
p{
    text-align:center;
    font-size:13px;
    margin-top:15px;
}

a{
    color:#1e9be2;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="box">

<h2>Login</h2>

<?php if (!empty($error)) : ?>
<p style="color:red;text-align:center"><?= $error ?></p>
<?php endif; ?>

<form method="POST">

    <label>Username</label>
    <input type="text" name="username" placeholder="masukkan Username" required>

    <label>Password</label>
    <input type="password" name="password" placeholder="masukkan Password" required>

    <button type="submit" name="login">Login</button>

</form>

<p>Belum punya akun? silahkan <a href="register.php">Daftar</a></p>

</div>

</body>
</html>
