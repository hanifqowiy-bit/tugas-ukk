<?php
session_start(); 
// Memulai session agar hanya user login yang dapat mengakses halaman

include "../config.php";
// Menghubungkan ke database

// CEK ROLE USER (hanya admin yang boleh masuk)
if($_SESSION['role']!='admin'){
 header("Location: ../login.php"); // jika bukan admin --> redirect
 exit;
}

/* ================= TAMBAH ================= */
if(isset($_POST['tambah'])){ // jika tombol tambah ditekan

 $nama  = $_POST['nama'];   // nama produk baru
 $harga = $_POST['harga'];  // harga produk
 $stok  = $_POST['stok'];   // stok awal

 $foto = $_FILES['foto']['name'];      // nama file foto
 $tmp  = $_FILES['foto']['tmp_name'];  // lokasi file sementara

 // upload file ke folder assets
 move_uploaded_file($tmp,"../../assets/".$foto);

 // insert produk baru ke database
 mysqli_query($koneksi,"
  INSERT INTO products(name,price,stock,photo)
  VALUES('$nama','$harga','$stok','$foto')
 ");
}


/* ================= UPDATE ================= */
if(isset($_POST['update'])){ // jika tombol update ditekan

 $id      = $_POST['id'];       // id produk
 $nama    = $_POST['nama'];     // nama baru
 $harga   = $_POST['harga'];    // harga baru
 $sisa    = $_POST['stok'];     // sisa stok di tampilan
 $terjual = $_POST['terjual'];  // jumlah terjual

 // menghitung stok asli (sisa stok + jumlah terjual)
 $stok_baru = $sisa + $terjual;

 // jika user mengganti foto
 if($_FILES['foto']['name']!=""){

  $foto = $_FILES['foto']['name'];
  $tmp  = $_FILES['foto']['tmp_name'];

  move_uploaded_file($tmp,"../../assets/".$foto);

  // update dengan foto baru
  mysqli_query($koneksi,"
   UPDATE products SET
   name='$nama',
   price='$harga',
   stock='$stok_baru',
   photo='$foto'
   WHERE id='$id'
  ");

 }else{

  // update tanpa mengubah foto
  mysqli_query($koneksi,"
   UPDATE products SET
   name='$nama',
   price='$harga',
   stock='$stok_baru'
   WHERE id='$id'
  ");
 }
}


/* ================= HAPUS ================= */
if(isset($_GET['hapus'])){ // jika tombol hapus ditekan

 // hapus data produk berdasarkan id
 mysqli_query($koneksi,"
  DELETE FROM products WHERE id='$_GET[hapus]'
 ");

 header("Location: manajemen_produk.php"); // kembali ke halaman utama
 exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Manajemen Produk</title>

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}

body{background:#f2f4f7}

.wrapper{display:flex;height:100vh}

/* SIDEBAR */
.sidebar{
 width:230px;
 background:#2196f3;
 color:white;
 padding:20px;
}

.sidebar h2{text-align:center;margin-bottom:30px}

.sidebar a{
 display:block;
 color:white;
 text-decoration:none;
 padding:10px;
 border-radius:5px;
}

.sidebar a:hover,
.active{
 background:rgba(255,255,255,.2);
}

/* MAIN */
.main{flex:1;padding:20px;background:white}

/* HEADER */
.header{
 background:#2196f3;
 color:white;
 padding:15px;
 border-radius:5px;
 margin-bottom:15px;
}

/* BUTTON */
.btn{
 padding:6px 12px;
 border:none;
 border-radius:4px;
 cursor:pointer;
}

.btn-add{background:#2196f3;color:white}
.btn-edit{background:#0992C2;color:white}
.btn-del{background:#f44336;color:white}

/* TABLE */
table{width:100%;border-collapse:collapse}

th,td{
 border:1px solid #ddd;
 padding:8px;
 text-align:center;
}

th{background:#2196f3;color:white}

img{
 width:60px;
 height:60px;
 object-fit:cover;
 border-radius:5px;
}

/* MODAL */
.modal{
 display:none;
 position:fixed;
 top:0;left:0;
 width:100%;height:100%;
 background:rgba(0,0,0,.5);
 justify-content:center;
 align-items:center;
}

.modal-box{
 background:white;
 width:350px;
 padding:20px;
 border-radius:8px;
}

.modal-box h3{text-align:center;margin-bottom:10px}

.modal-box input{
 width:100%;
 padding:8px;
 margin:5px 0;
 border:1px solid #ccc;
 border-radius:5px;
}

.close{
 float:right;
 cursor:pointer;
 color:red;
}

</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">

<h2>KOWI-MART</h2>

<!-- MENU -->
<a href="dashboard.php">Dashboard</a>
<a href="manajemen_user.php">Manajemen User</a>
<a href="laporan.php">Laporan</a>
<a href="manajemen_produk.php" class="active">Manajemen Produk</a>
<a href="manajemen_transaksi.php">Manajemen Transaksi</a>
<a href="backup_restore.php">Backup / Restore</a>

<br><br>

<a href="../logout.php">Keluar</a>

</div>

<!-- MAIN -->
<div class="main">

<div class="header">
<h3>Manajemen Produk</h3>
</div>

<!-- tombol buka modal tambah produk -->
<button class="btn btn-add" onclick="openTambah()">
+ Tambah Produk
</button>

<br><br>

<table>

<tr>
 <th>No</th>
 <th>Nama</th>
 <th>Harga</th>
 <th>Sisa Stok</th>
 <th>Foto</th>
 <th>Aksi</th>
</tr>

<?php

$no=1; // nomor urut tabel

// Query mengambil produk + stok + terjual + sisa stok
$q = mysqli_query($koneksi,"
SELECT 
 p.id,
 p.name,
 p.price,
 p.stock,
 p.photo,
 IFNULL(SUM(t.jumlah),0) AS terjual,
 (p.stock - IFNULL(SUM(t.jumlah),0)) AS sisa_stok
FROM products p
LEFT JOIN transaksi t 
 ON p.name = t.nama_produk
GROUP BY p.id
");

while($p=mysqli_fetch_assoc($q)){
?>

<tr>
 <td><?= $no++ ?></td>
 <td><?= $p['name'] ?></td>
 <td>Rp <?= number_format($p['price']) ?></td>
 <td><?= $p['sisa_stok'] ?></td>

 <td>
    <!-- Menampilkan foto produk -->
    <img src="../../assets/<?= $p['photo'] ?>">
 </td>

 <td>

 <!-- tombol edit (memanggil modal edit + isi data ke form) -->
 <button class="btn btn-edit"
 onclick="openEdit(
 '<?= $p['id'] ?>',
 '<?= $p['name'] ?>',
 '<?= $p['price'] ?>',
 '<?= $p['sisa_stok'] ?>',
 '<?= $p['terjual'] ?>'
 )">
 Edit
 </button>

 <!-- tombol hapus -->
 <a href="?hapus=<?= $p['id'] ?>"
 onclick="return confirm('Hapus produk?')"
 class="btn btn-del">
 Hapus
 </a>

 </td>
</tr>

<?php } ?>

</table>

</div>
</div>


<!-- MODAL TAMBAH PRODUK -->
<div class="modal" id="modalTambah">

<div class="modal-box">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Tambah Produk</h3>

<form method="POST" enctype="multipart/form-data">

<input name="nama" placeholder="Nama Produk" required>
<input name="harga" type="number" placeholder="Harga" required>
<input name="stok" type="number" placeholder="Stok Awal" required>

<input type="file" name="foto" required>

<button name="tambah" class="btn btn-add">Simpan</button>

</form>

</div>
</div>


<!-- MODAL EDIT PRODUK -->
<div class="modal" id="modalEdit">

<div class="modal-box">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Edit Produk</h3>

<form method="POST" enctype="multipart/form-data">

<!-- hidden data -->
<input type="hidden" name="id" id="eid">
<input type="hidden" name="terjual" id="eterjual">

<input name="nama" id="enama" required>
<input name="harga" type="number" id="eharga" required>
<input name="stok" type="number" id="estok" required>

<input type="file" name="foto">

<small>Kosongkan jika tidak ganti foto</small>

<br><br>

<button name="update" class="btn btn-edit">Update</button>

</form>

</div>
</div>


<script>

// buka modal tambah produk
function openTambah(){
 document.getElementById('modalTambah').style.display='flex';
}

// buka modal edit + mengisi form
function openEdit(id,nama,harga,stok,terjual){

 document.getElementById('eid').value=id;
 document.getElementById('enama').value=nama;
 document.getElementById('eharga').value=harga;
 document.getElementById('estok').value=stok;
 document.getElementById('eterjual').value=terjual;

 document.getElementById('modalEdit').style.display='flex';
}

// menutup kedua modal
function closeModal(){
 document.getElementById('modalTambah').style.display='none';
 document.getElementById('modalEdit').style.display='none';
}

</script>

</body>
</html>