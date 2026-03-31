<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pesanan Diproses</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f5f5f5;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px 40px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}

.check{
    font-size:60px;
    color:green;
    margin-bottom:15px;
}

.btn{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    background:#2196f3;
    color:white;
    padding:10px 25px;
    border-radius:6px;
}
</style>

</head>

<body>

<div class="box">

<div class="check">✔</div>

<h2>Barang akan diproses</h2>

<a href="dashboard.php" class="btn">
Kembali ke Dashboard
</a>

</div>

</body>
</html>
