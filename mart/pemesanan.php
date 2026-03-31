<?php
// ===============================
// MEMULAI SESSION
// ===============================
session_start();

// Menghubungkan ke database
include "config.php";

/* 
   MENYIMPAN HALAMAN ASAL
   Digunakan untuk kembali ke halaman pemesanan
*/
$_SESSION['back_to'] = 'pemesanan.php';

// Mengecek apakah user sudah login
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

// Mengambil semua data produk dari database
$produk = mysqli_query($koneksi,"SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
<title>Pemesanan</title>

<!-- CDN Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>

/* ===============================
   RESET CSS DASAR
================================ */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f2f6f9;
}

/* ===============================
   CONTAINER UTAMA
================================ */
.container{
    display:flex;
    min-height:100vh;
    width:100%;
}

/* ===============================
   SIDEBAR
================================ */
.sidebar{
    width:220px;
    background:#1e9bd7;
    min-height:100vh;
    color:white;
    padding:20px;
    position:sticky;
    top:0;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

/* Menu sidebar */
.sidebar a{
    display:flex;
    align-items:center;
    gap:8px;
    color:white;
    text-decoration:none;
    padding:12px;
    margin-bottom:10px;
    border-radius:6px;
}

.sidebar a:hover,
.sidebar .active{
    background:rgba(255,255,255,0.2);
}

/* ===============================
   CONTENT
================================ */
.content{
    flex:1;
    padding:25px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h1{
    font-size:32px;
}

/* Tombol kanan header */
.header-right a{
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:#1e9bd7;
    color:white;
    padding:8px 15px;
    border-radius:20px;
    text-decoration:none;
    margin-left:10px;
    font-size:14px;
}

.header-right a:hover{
    background:#157db3;
}

/* ===============================
   PRODUK
================================ */
.title{
    font-size:20px;
    margin-bottom:15px;
}

/* Grid produk */
.produk-list{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
}

/* Card produk */
.card{
    background:white;
    border-radius:12px;
    box-shadow:0 3px 8px rgba(0,0,0,0.15);
    overflow:hidden;
    transition:.2s;
}

.card:hover{
    transform:translateY(-5px);
}

/* Gambar produk */
.card img{
    width:100%;
    height:180px;
    object-fit:cover;
}

/* Isi card */
.card-body{
    padding:12px;
    text-align:center;
}

.card-body h3{
    font-size:16px;
    margin-bottom:5px;
}

/* Harga */
.price{
    color:#1e9bd7;
    font-weight:bold;
    margin-bottom:10px;
}

/* ===============================
   BUTTON
================================ */
.btn-group{
    display:flex;
    justify-content:space-between;
}

.btn-cart,
.btn-buy{
    flex:1;
    border:none;
    padding:7px;
    font-size:13px;
    cursor:pointer;
    border-radius:5px;
    margin:0 2px;
}

.btn-cart{
    background:#ddd;
}

.btn-buy{
    background:#1e9bd7;
    color:white;
}

.btn-buy:hover{
    background:#157db3;
}

/* ===============================
   POPUP
================================ */
.popup{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.popup-box{
    background:white;
    width:350px;
    padding:25px;
    border-radius:10px;
    text-align:center;
}

.popup-btn{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
}

.btn-transfer,
.btn-cod{
    width:45%;
    padding:10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
    background:#2196f3;
}

.btn-close{
    margin-top:15px;
    background:#aaa;
    color:white;
    border:none;
    padding:7px 15px;
    border-radius:5px;
    cursor:pointer;
}

.judul-produk{
    font-size:20px;
    margin-bottom:15px;
    color:#333;
    border-left:5px solid #169bd5;
    padding-left:10px;
}

</style>

<script>

// Menyimpan ID produk sementara saat tombol beli ditekan
let idProduk = "";

/* 
   Membuka popup pembayaran
   dan menyimpan id produk
*/
function openPopup(id){
    idProduk = id;
    document.getElementById("popup").style.display = "flex";
}

/* Menutup popup */
function closePopup(){
    document.getElementById("popup").style.display = "none";
}

/* 
   Mengarahkan user ke halaman sesuai metode pembayaran
*/
function pilihMetode(metode){

    if(metode === "Transfer"){
        window.location.href = "configure_transfer.php?id=" + idProduk;
    }
    else if(metode === "COD"){
        window.location.href = "configure_cod.php?id=" + idProduk;
    }

}

</script>

</head>
<body>

<div class="container">

<!-- ===============================
     SIDEBAR
================================ -->
<div class="sidebar">

    <h2>KOWI-MART</h2>

    <!-- Menu Home -->
    <a href="dashboard.php">
        <i data-lucide="home"></i> Home
    </a>

    <!-- Menu Pemesanan -->
    <a href="pemesanan.php" class="active">
        <i data-lucide="shopping-cart"></i> Pemesanan
    </a>

    <!-- Menu Logout -->
    <a href="logout.php">
        <i data-lucide="log-out"></i> Keluar
    </a>

</div>

<!-- ===============================
     CONTENT
================================ -->
<div class="content">

<div class="header">

    <div>
        <h1>Selamat</h1>
        <p>Berbelanja</p>
    </div>

    <div class="header-right">

        <!-- Tombol Keranjang -->
        <a href="keranjang.php">
            <i data-lucide="shopping-cart"></i> Keranjang
        </a>

        <!-- Tombol Riwayat -->
        <a href="riwayat.php">
            <i data-lucide="history"></i> Riwayat
        </a>

    </div>

</div>

<div class="judul-produk">
    Produk
</div>

<div class="produk-list">

<?php while($p=mysqli_fetch_assoc($produk)): ?>

<div class="card">

    <!-- Menampilkan gambar produk -->
    <img src="../assets/<?php echo $p['photo']; ?>">

    <div class="card-body">

        <!-- Nama produk -->
        <h3><?php echo $p['name']; ?></h3>

        <!-- Harga produk -->
        <div class="price">
            Rp <?php echo number_format($p['price']); ?>
        </div>

        <div class="btn-group">

            <!-- Tombol tambah ke keranjang -->
            <a href="keranjang.php?add=<?php echo $p['id']; ?>">
                <button class="btn-cart">
                    <i data-lucide="shopping-cart"></i>
                </button>
            </a>

            <!-- Tombol beli langsung -->
            <button class="btn-buy"
            onclick="openPopup('<?php echo $p['id']; ?>')">
                Beli
            </button>

        </div>

    </div>

</div>

<?php endwhile; ?>

</div>

</div>

</div>

<!-- ===============================
     POPUP PEMBAYARAN
================================ -->
<div class="popup" id="popup">

    <div class="popup-box">

        <h3>Pilih Metode Pembayaran</h3>

        <div class="popup-btn">

            <!-- Transfer -->
            <button class="btn-transfer"
            onclick="pilihMetode('Transfer')">
                Transfer
            </button>

            <!-- COD -->
            <button class="btn-cod"
            onclick="pilihMetode('COD')">
                COD
            </button>

        </div>

        <!-- Tombol batal -->
        <button class="btn-close" onclick="closePopup()">
            Batal
        </button>

    </div>

</div>

<!-- Mengaktifkan Lucide Icons -->
<script>
    lucide.createIcons();
</script>

</body>
</html>