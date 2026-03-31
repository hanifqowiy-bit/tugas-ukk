<?php
// ===============================
// MEMULAI SESSION
// ===============================
session_start();

// Menghubungkan ke database
include "config.php";

// Mengambil ID user dari session login
$user_id = $_SESSION['user_id'];

// Mengambil data user berdasarkan ID
$q = mysqli_query($koneksi,"SELECT * FROM users WHERE id='$user_id'");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<!-- CDN Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>

/* ===============================
   RESET CSS
================================ */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

/* ===============================
   BODY BACKGROUND
================================ */
body{
    background:linear-gradient(to right,#1e9bd7,#45b3e7);
    min-height:100vh;
}

/* ===============================
   CONTAINER UTAMA
================================ */
.container{
    display:flex;
}

/* ===============================
   SIDEBAR PROFILE
================================ */
.profile-side{
    width:350px;
    background:white;
    min-height:100vh;
    padding:25px;
    border-radius:0 30px 30px 0;
    position:relative;
    box-shadow:0 0 15px rgba(0,0,0,0.15);
}

/* Tombol judul Profile */
.btn-profile{
    background:#1e9bd7;
    color:white;
    padding:7px 22px;
    border-radius:20px;
    display:inline-block;
    font-size:14px;
    margin-bottom:10px;
}

/* Judul informasi akun */
.title{
    margin:15px 0;
    font-size:18px;
    color:#333;
}

/* ===============================
   AVATAR USER
================================ */
.avatar-box{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:20px;
    padding-bottom:15px;
    border-bottom:1px solid #ddd;
}

/* Lingkaran avatar */
.avatar{
    width:70px;
    height:70px;
    border:3px solid #1e9bd7;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* Teks username */
.user-text{
    font-size:14px;
}

/* ===============================
   FORM INFORMASI USER
================================ */
.form-group{
    margin-bottom:18px;
}

label{
    display:block;
    font-size:14px;
    margin-bottom:5px;
}

input{
    width:100%;
    padding:9px;
    border:1px solid #999;
    border-radius:6px;
    font-size:14px;
    transition:0.3s;
}

input:hover{
    border-color:#1e9bd7;
}

/* ===============================
   TOMBOL KEMBALI
================================ */
.back{
    position:absolute;
    bottom:25px;
    left:25px;
    font-size:30px;
    font-weight:bold;
    text-decoration:none;
    color:#1e9bd7;
    transition:0.3s;
}

.back:hover{
    color:#0c6fa1;
}

/* Konten kanan kosong sesuai desain */
.content{
    flex:1;
}

</style>

</head>
<body>

<div class="container">

    <!-- ===============================
         SIDEBAR PROFILE
    ================================= -->
    <div class="profile-side">

        <!-- Label Profile -->
        <div class="btn-profile">Profile</div>

        <h3 class="title">Informasi Akun</h3>

        <!-- Avatar dan Username -->
        <div class="avatar-box">

            <!-- Icon User (Lucide menggantikan emoji 👤) -->
            <div class="avatar">
                <i data-lucide="user"></i>
            </div>

            <div class="user-text">
                <!-- Menampilkan username dari database -->
                <b><?php echo $data['username']; ?></b><br>
                User
            </div>

        </div>

        <!-- Informasi Username -->
        <div class="form-group">
            <label>Username</label>
            <input type="text" value="<?php echo $data['username']; ?>" readonly>
        </div>

        <!-- Informasi Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="text" value="<?php echo $data['email']; ?>" readonly>
        </div>

        <!-- Tombol kembali ke dashboard (menggantikan ← emoji) -->
        <a href="dashboard.php" class="back">
            <i data-lucide="arrow-left"></i>
        </a>

    </div>

    <!-- ===============================
         KONTEN KANAN (KOSONG)
    ================================= -->
    <div class="content">
        <!-- Kosong sesuai desain -->
    </div>

</div>

<!-- Mengaktifkan Lucide Icons -->
<script>
    lucide.createIcons();
</script>

</body>
</html>