<?php
    include __DIR__ . '/../config/config.php';

    // Tidak perlu session_start() di sini karena tidak ada interaksi session

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: /VALORANT/public/user/register.php');
        exit;
    }

    // Validasi input nama tidak boleh kosong
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($name === '' || $email === '' || $password === ''){
        header('Location: /VALORANT/public/user/register.php?error=' . urlencode('All fields must be filled.'));
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header('Location: /VALORANT/public/user/register.php?error=' . urlencode('Email is not valid.'));
        exit;
    }

    if(strlen($password) < 8){
        header('Location: /VALORANT/public/user/register.php?error=' . urlencode('Password must be 8 characters or more.'));
        exit;
    }

    // Menggunakan tabel 'users' dan kolom yang sesuai
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0){
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: /VALORANT/public/user/register.php?error=' . urlencode('Email has been registered already.'));
        exit;
    }
    mysqli_stmt_close($stmt);

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    // Menggunakan tabel 'users' dan kolom yang sesuai
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    if(!$stmt){
        header('Location: /VALORANT/public/user/register.php?error=' . urlencode('Server error, please try again.'));
        exit;
    }
    mysqli_stmt_bind_param($stmt,"sss",$name,$email,$password_hash);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if($ok){
        // Arahkan ke halaman login dengan pesan sukses
        header('Location: /VALORANT/public/user/login.php?success=' . urlencode('Registration successful! Please log in.'));
        exit;
    }
    else{
        header('Location: /VALORANT/public/user/register.php?error=' . urlencode('Failed to create account. Please try again.'));
        exit;
    }
?>