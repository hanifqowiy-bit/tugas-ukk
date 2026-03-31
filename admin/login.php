<?php
session_start(); // mulai sesi
include "config.php"; // koneksi database

$error = ""; // variabel untuk menampung pesan error

// cek jika tombol login ditekan
if(isset($_POST['login'])){

  $username = $_POST['username']; // ambil username input
  $password = $_POST['password']; // ambil password input

  // ambil data user berdasarkan username
  $q = mysqli_query($koneksi,
   "SELECT * FROM users WHERE username='$username'");

  $data = mysqli_fetch_assoc($q); // ambil data user

  // cek apakah user ada
  if($data){

    // cek password
    if(password_verify($password, $data['password'])){

      // set session login
      $_SESSION['id']   = $data['id'];
      $_SESSION['role'] = $data['role'];

      // arahkan ke dashboard admin jika role benar
      if($data['role'] == 'admin'){
        header("Location: admin/dashboard.php");
      }else{
        $error = "Bukan admin!"; // role bukan admin
      }

    }else{
      $error = "Password salah!"; // password tidak cocok
    }

  }else{
    $error = "Username tidak ditemukan!"; // username tidak ada
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Admin</title>

<style>
body{
  font-family: Arial;
  background:#2196f3; /* warna background */
}

.login-box{
  width:350px;
  background:white;
  padding:30px;
  margin:100px auto;
  border-radius:10px;
  box-shadow:0 0 10px rgba(0,0,0,0.2); /* efek bayangan */
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
  background:#2196f3; /* warna tombol */
  color:white;
  border:none;
  border-radius:5px;
  cursor:pointer;
}

button:hover{
  background:#1976d2; /* efek hover */
}

.error{
  color:red; /* warna tulisan error */
  text-align:center;
}
</style>
</head>

<body>

<div class="login-box">

<h2>Login Admin</h2>

<?php if($error){ ?>
<!-- tampilkan pesan error jika ada -->
<p class="error"><?= $error ?></p>
<?php } ?>

<form method="POST">

<!-- input username -->
<input type="text" name="username" placeholder="Username" required>

<!-- input password -->
<input type="password" name="password" placeholder="Password" required>

<!-- tombol login -->
<button name="login">Login</button>

</form>

</div>

</body>
</html>