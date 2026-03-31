<?php 
session_start();
include "config.php";

$error = "";

// Proses Login
if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $q = mysqli_query($koneksi,"
        SELECT * FROM users 
        WHERE username='$username' 
        AND role='petugas'
        LIMIT 1
    ");

    if(mysqli_num_rows($q) == 1){

        $u = mysqli_fetch_assoc($q);

        if(password_verify($password, $u['password'])){

            // SESSION KHUSUS PETUGAS (tidak bentrok dengan admin)
            $_SESSION['petugas_id']       = $u['id'];
            $_SESSION['petugas_username'] = $u['username'];
            $_SESSION['petugas_role']     = $u['role'];
            $_SESSION['petugas_login']    = true;

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Akun ini bukan petugas!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Pertugas</title>

<style>
body{
  font-family: Arial;
  background:#2196f3;
}

.login-box{
  width:350px;
  background:white;
  padding:30px;
  margin:100px auto;
  border-radius:10px;
  box-shadow:0 0 10px rgba(0,0,0,0.2);
}

.login-box h2{
  text-align:center;
}

input{
  width:100%;
  padding:10px;
  margin:10px 0;
  border:1px solid #ccc;
  border-radius:5px;
}

button{
  width:100%;
  padding:10px;
  background:#2196f3;
  color:white;
  border:none;
  border-radius:5px;
  cursor:pointer;
}

button:hover{
  background:#1976d2;
}

.error{
  color:red;
  text-align:center;
}
</style>
</head>

<body>

<div class="login-box">

<h2>Login Petugas</h2>

<?php if($error){ ?>
<p class="error"><?= $error ?></p>
<?php } ?>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

</form>

</div>

</body>
</html>
