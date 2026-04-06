<?php
session_start(); // Mulai session
include "config.php"; // Koneksi database

/* CEK LOGIN PETUGAS */
// Mengecek apakah petugas sudah login, role benar, dan session valid
if(
   !isset($_SESSION['petugas_login']) || // Jika belum login
   $_SESSION['petugas_login'] !== true || // Jika login tidak valid
   $_SESSION['petugas_role'] !== 'petugas' // Jika role bukan petugas
){
    header("Location: login.php"); // Arahkan ke halaman login
    exit();
}

/* ============================
   AMBIL NAMA PETUGAS
   ============================ */
// Ambil nama petugas dari session, jika kosong pakai "Petugas"
$nama = $_SESSION['petugas_username'] ?? 'Petugas';

/* ============================
   HITUNG DATA DI DATABASE
   ============================ */
// Hitung total produk
$produk    = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM products"));
// Hitung total transaksi
$transaksi = mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM transaksi"));
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard Petugas</title>

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

/* Background utama */
body{background:#f2f4f7}

/* Wrapper utama: sidebar + konten */
.wrapper{display:flex;height:100vh}

/* SIDEBAR */
.sidebar{
 width:230px;
 background:#2196f3;
 color:white;
 padding:20px;
}

.sidebar h2{text-align:center;margin-bottom:30px}

/* Menu sidebar */
.sidebar a{
 display:block;
 color:white;
 text-decoration:none;
 padding:10px;
 border-radius:5px;
}

/* Hover dan menu aktif */
.sidebar a:hover,
.active{
 background:rgba(255,255,255,.2);
}

/* MAIN CONTENT */
.main{
 flex:1;
 padding:20px;
 background:white;
}

/* HEADER ATAS */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:20px;
}

/* BOX WRAPPER */
.box{
 display:flex;
 gap:20px;
 flex-wrap:wrap;
}

/* CARD */
.card{
 background:white;
 width:220px;
 padding:20px;
 border-radius:10px;
 text-align:center;
 box-shadow:0 2px 5px rgba(0,0,0,0.2);
}

.card h3{
 margin:0;
 color:#555;
}

.card p{
 font-size:32px;
 margin-top:10px;
 color:#2196f3;
 font-weight:bold;
}

/* ANIMASI CARD */
.card{
 transition:0.3s ease;
 animation: fadeUp 0.6s ease;
}

.card:hover{
 transform:translateY(-5px);
 box-shadow:0 6px 15px rgba(0,0,0,0.2);
}

.card p{
 animation: pop 0.6s ease;
}

/* KEYFRAMES ANIMASI */
@keyframes fadeUp{
 from{
  opacity:0;
  transform:translateY(20px);
 }
 to{
  opacity:1;
  transform:translateY(0);
 }
}

@keyframes pop{
 0%{transform:scale(0.8);}
 70%{transform:scale(1.1);}
 100%{transform:scale(1);}
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<h2>KOWI-MART</h2> <!-- Judul sidebar -->

<!-- Menu navigasi -->
<a href="dashboard.php" class="active">Dashboard</a>
<a href="transaksi.php">Transaksi</a>
<a href="produk.php">Data Produk</a>
<a href="laporan_petugas.php">Laporan</a>

<br><br>

<a href="logout.php">Keluar</a> <!-- Logout -->

</div>

<!-- MAIN CONTENT -->
<div class="main">

<div class="header">
<h3>Dashboard Petugas</h3>
</div>

<h2>Selamat Datang, <?= $nama ?> 👋</h2>

<br>

<div class="box">

    <!-- Card total produk -->
    <div class="card">
        <h3>Total Produk</h3>
        <p><?= $produk ?></p>
    </div>

    <!-- Card total transaksi -->
    <div class="card">
        <h3>Total Transaksi</h3>
        <p><?= $transaksi ?></p>
    </div>

</div>

</div>
</div>

</body>
</html>
