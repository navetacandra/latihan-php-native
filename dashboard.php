<?php
include './koneksi.php';

// cek jika user tidak memiliki session
if(!isset($_SESSION['user']['id'])) {
    // redirect user ke halaman login (./index.php)
    header('location: ./index.php');
}

if(isset($_POST['logout'])) { // jika data logout terkirim dengan method post
    // hapus session user
    unset($_SESSION['user']);
    // redirect ke halaman login (./index.php)
    header('location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- import css -->
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- judul halaman (header) -->
    <h1>DASHBOARD</h1>
    <!-- membuat form logout -->
    <form action="./dashboard.php" method="post">
        <!-- tombol submit untuk logout -->
        <button type="submit" name="logout">LOG OUT</button>
    </form>
</body>
</html>