<?php
session_start(); // Mulai session

include "config.php"; // Koneksi database

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])){
    header("Location:index.php"); // Jika belum login, kembali ke login
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil ID user dari session

// Ambil semua transaksi milik user berdasarkan ID user
$data = mysqli_query($koneksi,"
SELECT * FROM transaksi
WHERE user_id='$user_id'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Transaksi</title>

<style>

/* Styling halaman utama */
body{
    margin:0;
    font-family:Arial;
    background:#f2f6f9;
}

/* Header biru atas */
.header{
    background:#1e9bd7;
    color:white;
    padding:15px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
}

/* Container box putih */
.container{
    width:80%;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

/* Tabel */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#1e9bd7;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border-bottom:1px solid #ccc;
    text-align:center;
}

/* Tombol kembali */
.back{
    display:inline-block;
    margin-top:20px;
    font-size:22px;
    text-decoration:none;
    color:#1e9bd7;
}

.back:hover{
    color:#157db3;
}

/* Jika kosong */
.empty{
    text-align:center;
    padding:20px;
    color:#777;
}

</style>
</head>

<body>

<div class="header">
KOWI-MART <!-- Judul header -->
</div>

<div class="container">

<h2>Riwayat Transaksi</h2>

<br>

<table>

<tr>
    <th>Nama Produk</th>
    <th>Harga</th>
    <th>Jumlah</th>
</tr>

<?php if(mysqli_num_rows($data) > 0): ?> 
<!-- Jika transaksi ada -->

<?php while($t = mysqli_fetch_assoc($data)): ?> 
<!-- Loop setiap transaksi -->

<tr>
    <td><?php echo $t['nama_produk']; ?></td>
    <td>Rp <?php echo number_format($t['harga']); ?></td>
    <td><?php echo $t['jumlah']; ?></td>
</tr>

<?php endwhile; ?>

<?php else: ?> 
<!-- Jika tidak ada transaksi -->

<tr>
    <td colspan="3" class="empty">
        Belum ada transaksi
    </td>
</tr>

<?php endif; ?>

</table>

<a href="pemesanan.php" class="back">← Kembali</a>
<!-- Tombol kembali -->

</div>

</body>
</html>
