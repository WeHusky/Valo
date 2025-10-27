<?php
// Memulai atau melanjutkan sesi yang ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menghapus semua variabel sesi
$_SESSION = [];

// Menghancurkan sesi
session_destroy();

// Mengarahkan pengguna kembali ke halaman welcome atau login
header('Location: /VALORANT/public/user/welcome.php');
exit;
?>