<?php
    require_once __DIR__ . '/../config/config.php';

    if (session_status() === PHP_SESSION_NONE) {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../public/user/login.php');
        exit;
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($email === '' || $password === ''){
        header('Location: ../public/user/login.php?error=' . urlencode('Email & password fields must be filled.'));
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header('Location: ../public/user/login.php?error=' . urlencode('Email is not valid.'));
        exit;
    }

 
    $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1");
    if(!$stmt){
        header('Location: ../public/user/login.php?error=' . urlencode('Server error, please try again later.'));
        exit;
    }

    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) === 0){
        mysqli_stmt_close($stmt);
        header('Location: ../public/user/login.php?error=' . urlencode('Wrong Email or Password.'));
        mysqli_close($conn);
        exit;
    }

    mysqli_stmt_bind_result($stmt, $id, $name, $password_hash, $role);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($conn);
    if (!password_verify($password, $password_hash)) {
        header('Location: ../public/user/login.php?error=' . urlencode('Wrong Email or Password.'));
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = $role;
    $_SESSION['last_login'] = date('Y-m-d H:i:s');

    header('Location: ../public/user/homepage.php');
    exit;