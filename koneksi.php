<?php
// memulai session
session_start();
// membuat koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "latihan_candra_native", 3306);

// tampilkan jika gagal koneksi ke database
if (mysqli_connect_errno()) {
   echo mysqli_connect_error();
}
