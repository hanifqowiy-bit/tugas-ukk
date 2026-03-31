<?php
include "config.php";

$id = $_GET['id'];

/* Ambil data transaksi + user */
$q = mysqli_query($koneksi, "
    SELECT transaksi.*, users.username
    FROM transaksi
    JOIN users ON transaksi.user_id = users.id
    WHERE transaksi.id = '$id'
");

$t = mysqli_fetch_assoc($q);
$total = $t['harga'] * $t['jumlah'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Konfirmasi Pembayaran</title>

<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial, sans-serif;
    }

    body{
        background:#f0f4f8;
        padding:40px;
        display:flex;
        justify-content:center;
    }

    .container{
        background:white;
        width:500px;
        padding:25px;
        border-radius:10px;
        box-shadow:0 5px 15px rgba(0,0,0,0.15);
    }

    h2{
        text-align:center;
        margin-bottom:20px;
        color:#2196f3;
        font-size:22px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        margin-bottom:20px;
    }

    td{
        padding:10px;
        border-bottom:1px solid #eee;
        font-size:15px;
    }

    td:first-child{
        font-weight:bold;
        width:40%;
        color:#333;
    }

    .btn{
        display:inline-block;
        padding:10px 15px;
        background:#2196f3;
        color:white;
        text-decoration:none;
        border-radius:6px;
        margin-right:5px;
        transition:0.2s;
    }

    .btn:hover{
        background:#0d7adf;
    }

    .reject{
        background:#e53935;
    }
    .reject:hover{
        background:#c62828;
    }

    .center{
        text-align:center;
    }
</style>

</head>
<body>

<div class="container">

<h2>Detail Konfirmasi Pembayaran</h2>

<table>
<tr><td>ID Transaksi</td><td><?= $t['id'] ?></td></tr>
<tr><td>Username</td><td><?= $t['username'] ?></td></tr>
<tr><td>Jumlah</td><td><?= $t['jumlah'] ?></td></tr>
<tr><td>Harga</td><td>Rp <?= number_format($t['harga']) ?></td></tr>
<tr><td>Total</td><td>Rp <?= number_format($total) ?></td></tr>
<tr><td>Metode</td><td><?= !empty($t['bukti']) ? "TRANSFER" : "COD" ?></td></tr>

<tr>
<td>Bukti</td>
<td>
<?php if(!empty($t['bukti'])){ ?>
   <a class="btn" href="../assets/upload/<?= $t['bukti'] ?>" target="_blank">Lihat Bukti</a>
<?php }else{ ?>
   Tidak ada (COD)
<?php } ?>
</td>
</tr>

<tr><td>Tanggal</td><td><?= $t['tanggal'] ?></td></tr>
<tr><td>Status</td><td><?= $t['status'] ?></td></tr>
</table>

<div class="center">

<a href="update_status.php?id=<?= $t['id'] ?>&aksi=konfirmasi" class="btn">
    Konfirmasi
</a>

<a href="update_status.php?id=<?= $t['id'] ?>&aksi=tolak" class="btn reject">
    Tolak
</a>

<br><br>

<a href="transaksi.php" class="btn">Kembali</a>

</div>

</div>

</body>
</html>