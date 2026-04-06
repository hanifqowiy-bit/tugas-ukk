<?php
session_start(); // Mulai session untuk akses user & cart
include "config.php"; // Koneksi database

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil ID user dari session

// Ambil data form
$nama   = $_POST['nama'];
$telp   = $_POST['telp'];
$alamat = $_POST['alamat'];
$tanggal = date("Y-m-d"); // Tanggal transaksi

// ================= UPLOAD BUKTI =================
// Siapkan variabel bukti kosong
$bukti = "";

if(isset($_FILES['bukti']) && $_FILES['bukti']['name'] != ""){

    $folder = "../assets/upload/"; // Folder upload bukti

    // Jika folder belum ada, buat folder
    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    // Buat nama file unik berdasarkan waktu
    $nama_file = time()."_".$_FILES['bukti']['name"];
    $tmp = $_FILES['bukti']['tmp_name'];

    // Pindahkan file dari temporary ke folder tujuan
    move_uploaded_file($tmp, $folder.$nama_file);

    $bukti = $nama_file; // Simpan nama file untuk database
}
// ================================================


// ================= MODE 1 : DARI PEMESANAN (1 PRODUK) =================
// Mode ini aktif jika checkout dari halaman satu produk
if(isset($_POST['id_produk'])){

    $id_produk = $_POST['id_produk'];

    // Ambil data produk dari database
    $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id_produk'");
    $p = mysqli_fetch_assoc($q);

    $nama_produk = $p['name'];
    $harga = $p['price'];
    $jumlah = 1; // Selalu 1 untuk mode single

    // Simpan transaksi ke database
    mysqli_query($koneksi,"

        INSERT INTO transaksi
        (user_id,nama_produk,harga,jumlah,alamat,bukti,tanggal)
        VALUES
        ('$user_id','$nama_produk','$harga','$jumlah','$alamat','$bukti','$tanggal')

    ");

}


// ================= MODE 2 : DARI KERANJANG (BANYAK PRODUK) =================
else if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){

    // Loop semua item di keranjang
    foreach($_SESSION['cart'] as $id=>$qty){

        $q = mysqli_query($koneksi,"SELECT * FROM products WHERE id='$id'");
        $p = mysqli_fetch_assoc($q);

        $nama_produk = $p['name'];
        $harga = $p['price'];
        $jumlah = $qty;

        // Simpan setiap item sebagai transaksi baru
        mysqli_query($koneksi,"

            INSERT INTO transaksi
            (user_id,nama_produk,harga,jumlah,alamat,bukti,tanggal)
            VALUES
            ('$user_id','$nama_produk','$harga','$jumlah','$alamat','$bukti','$tanggal')

        ");

    }

    // Hapus keranjang setelah checkout sukses
    unset($_SESSION['cart']);
}


// ================= REDIRECT =================
// Arahkan user ke halaman sukses
header("Location: proses_sukses.php");
exit();
?>
