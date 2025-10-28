<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Keamanan: Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "You are not authorized to perform this action.";
    exit;
}

require_once __DIR__ . '/../config/config.php';

// 2. Validasi Input: Cek apakah ID ada dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /VALORANT/public/user/maps.php?error=' . urlencode('Invalid map ID.'));
    exit;
}
$map_id = (int)$_GET['id'];

// 3. Ambil Nama File Gambar (Sebelum Menghapus dari DB)
$stmt_find = mysqli_prepare($conn, "SELECT image_map FROM maps WHERE id_map = ?");
mysqli_stmt_bind_param($stmt_find, "i", $map_id);
mysqli_stmt_execute($stmt_find);
$result = mysqli_stmt_get_result($stmt_find);
$map = mysqli_fetch_assoc($result);
$image_filename = null;

if ($map && !empty($map['image_map'])) {
    $image_filename = $map['image_map'];
}
mysqli_stmt_close($stmt_find);

// 4. Hapus Map dari Database
$stmt_delete = mysqli_prepare($conn, "DELETE FROM maps WHERE id_map = ?");
mysqli_stmt_bind_param($stmt_delete, "i", $map_id);

if (mysqli_stmt_execute($stmt_delete)) {
    // 5. Hapus File Gambar (Jika Hapus DB Berhasil)
    if ($image_filename) {
        $file_path = __DIR__ . '/../public/images/maps/' . $image_filename;
        
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // 6. Arahkan kembali dengan pesan sukses
    header('Location: /VALORANT/public/user/maps.php?success=' . urlencode('Map deleted successfully.'));
} else {
    // 6. Arahkan kembali dengan pesan error
    header('Location: /VALORANT/public/user/maps.php?error=' . urlencode('Failed to delete map.'));
}

mysqli_stmt_close($stmt_delete);
mysqli_close($conn);
exit;