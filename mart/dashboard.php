<?php
// Memulai session
session_start();

// Menghubungkan ke database
include "config.php";

// Mengecek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Jika belum login, kembali ke halaman login
    exit();
}

// Mengambil semua data produk dari database
$produk = mysqli_query($koneksi, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<!-- CDN Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>

/* Reset CSS dasar */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

/* Layout utama */
body{
    display:flex;
    background:#f4f6f9;
}

/* ===== SIDEBAR ===== */
.sidebar{
    width:220px;
    background:#169bd5;
    min-height:100vh;
    padding:20px;
    color:white;
}

/* Judul sidebar */
.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

/* Link menu sidebar */
.sidebar a{
    display:flex;
    align-items:center;
    gap:8px;
    padding:12px;
    color:white;
    text-decoration:none;
    border-radius:5px;
    margin-bottom:10px;
    transition:0.2s;
}

/* Hover dan menu aktif */
.sidebar a:hover,
.sidebar .active{
    background:rgba(255,255,255,0.2);
}

/* ===== CONTENT ===== */
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

.header h2{
    font-size:22px;
}

.header p{
    font-size:14px;
    color:#666;
}

/* PROFILE ICON */
.profile{
    width:42px;
    height:42px;
    border-radius:50%;
    background:#169bd5;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    cursor:pointer;
    text-decoration:none;
}

/* ===== JUDUL PRODUK ===== */
.judul-produk{
    font-size:20px;
    margin-bottom:15px;
    color:#333;
    border-left:5px solid #169bd5;
    padding-left:10px;
}

/* ===== PRODUK GRID ===== */
.produk{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap:25px;
}

/* CARD PRODUK */
.card{
    background:white;
    border-radius:12px;
    padding:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:all 0.3s ease;
}

/* Efek hover card */
.card:hover{
    transform:translateY(-6px);
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

/* Gambar produk */
.card img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
}

/* Nama produk */
.card h3{
    font-size:15px;
    margin:10px 0 5px;
}

/* Harga produk */
.card p{
    color:#169bd5;
    font-weight:bold;
    font-size:15px;
}

</style>
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
    <h2>KOWI-MART</h2>

    <!-- Menu Home -->
    <a href="dashboard.php" class="active">
        <i data-lucide="home"></i> Home
    </a>

    <!-- Menu Pemesanan -->
    <a href="pemesanan.php">
        <i data-lucide="shopping-cart"></i> Pemesanan
    </a>

    <!-- Menu Logout -->
    <a href="logout.php">
        <i data-lucide="log-out"></i> Keluar
    </a>
</div>

<!-- ===== CONTENT ===== -->
<div class="content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <!-- Judul Dashboard -->
            <h2>Selamat Datang di KOWI-MART 
                <i data-lucide="shopping-bag"></i>
            </h2>

            <!-- Subjudul -->
            <p>Belanja mudah, cepat, dan terpercaya.</p>
        </div>

        <!-- Icon Profile -->
        <a href="profile.php" class="profile">
            <i data-lucide="user"></i>
        </a>
    </div>

    <!-- ===== JUDUL PRODUK ===== -->
    <div class="judul-produk">
        Produk
    </div>

    <!-- ===== LIST PRODUK ===== -->
    <div class="produk">

        <?php while($p = mysqli_fetch_assoc($produk)): ?>
        <!-- Card produk -->
        <div class="card">
            <!-- Menampilkan gambar produk dari folder assets -->
            <img src="../assets/<?php echo $p['photo']; ?>">

            <!-- Menampilkan nama produk -->
            <h3><?php echo $p['name']; ?></h3>

            <!-- Menampilkan harga dengan format rupiah -->
            <p>Rp <?php echo number_format($p['price']); ?></p>
        </div>
        <?php endwhile; ?>

    </div>

</div>

<!-- Mengaktifkan Lucide Icons -->
<script>
    lucide.createIcons();
</script>

</body>
</html>