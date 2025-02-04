<?php
// koneksi.php
$host = 'localhost'; // atau IP database server
$user = 'root'; // username untuk koneksi ke DB
$pass = ''; // password untuk koneksi ke DB
$dbname = 'upn'; // ganti dengan nama database yang kamu pakai

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>