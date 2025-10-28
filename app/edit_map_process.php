<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Keamanan: Cek login
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "You are not authorized to perform this action.";
    exit;
}

require_once __DIR__ . '/../config/config.php';

// Cek request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /VALORANT/public/user/maps.php');
    exit;
}

// 1. Ambil semua data dari form
$id_map = (int)$_POST['id_map'];
$name_map = trim($_POST['name_map']);
$desc_map = trim($_POST['desc_map']);
$old_image_name = trim($_POST['old_image_name']);

// 2. Validasi data teks
if (empty($id_map) || empty($name_map) || empty($desc_map)) {
    header('Location: /VALORANT/public/user/edit_map.php?id=' . $id_map . '&error=' . urlencode('All fields are required.'));
    exit;
}

// 3. Logika Upload Gambar (Hanya jika gambar baru diupload)
$new_file_name = $old_image_name; // Default: pakai nama gambar lama

// Cek apakah ada file baru yang diupload
if (isset($_FILES['image_map']) && $_FILES['image_map']['error'] === UPLOAD_ERR_OK) {
    
    $file = $_FILES['image_map'];
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    // Validasi file baru
    if (!in_array($file_ext, $allowed_ext)) {
        header('Location: /VALORANT/public/user/edit_map.php?id=' . $id_map . '&error=' . urlencode('Invalid file type.'));
        exit;
    }
    if ($file_size > 5 * 1024 * 1024) { // 5MB
        header('Location: /VALORANT/public/user/edit_map.php?id=' . $id_map . '&error=' . urlencode('File size is too large. Max 5MB.'));
        exit;
    }
    
    // Buat nama file baru berdasarkan nama map
    $clean_map_name = strtolower(str_replace(' ', '_', $name_map));
    $new_file_name = $clean_map_name . '.' . $file_ext;
    $upload_destination = __DIR__ . '/../public/images/maps/' . $new_file_name;

    // Pindahkan file baru
    if (move_uploaded_file($file_tmp_name, $upload_destination)) {
        // Hapus file gambar LAMA jika namanya BERBEDA dari file baru
        if ($old_image_name && $old_image_name != $new_file_name) {
            $old_file_path = __DIR__ . '/../public/images/maps/' . $old_image_name;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }
    } else {
        header('Location: /VALORANT/public/user/edit_map.php?id=' . $id_map . '&error=' . urlencode('Failed to move uploaded file.'));
        exit;
    }
}

// 4. Update Database
$stmt = mysqli_prepare($conn, "UPDATE maps SET name_map = ?, desc_map = ?, image_map = ? WHERE id_map = ?");
mysqli_stmt_bind_param($stmt, "sssi", $name_map, $desc_map, $new_file_name, $id_map);

if (mysqli_stmt_execute($stmt)) {
    header('Location: /VALORANT/public/user/maps.php?success=' . urlencode('Map updated successfully!'));
} else {
    header('Location: /VALORANT/public/user/edit_map.php?id=' . $id_map . '&error=' . urlencode('Failed to update map.'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;