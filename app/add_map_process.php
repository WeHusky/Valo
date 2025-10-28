<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Amankan: hanya pengguna yang sudah login yang bisa memproses
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "You are not authorized to perform this action.";
    exit;
}

require_once __DIR__ . '/../config/config.php';

// Pastikan ini adalah request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /VALORANT/public/user/add_map.php');
    exit;
}

// --- 1. Ambil Data Teks ---
$name_map = trim($_POST['name_map']);
$desc_map = trim($_POST['desc_map']);

// --- 2. Validasi Data Teks ---
if (empty($name_map) || empty($desc_map)) {
    header('Location: /VALORANT/public/user/add_map.php?error=' . urlencode('All fields are required.'));
    exit;
}

// --- 3. Validasi File Upload ---
if (!isset($_FILES['image_map']) || $_FILES['image_map']['error'] !== UPLOAD_ERR_OK) {
    header('Location: /VALORANT/public/user/add_map.php?error=' . urlencode('Image upload failed or no image selected.'));
    exit;
}

$file = $_FILES['image_map'];
$file_tmp_name = $file['tmp_name'];
$file_size = $file['size'];

// Cek ekstensi file
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($file_ext, $allowed_ext)) {
    header('Location: /VALORANT/public/user/add_map.php?error=' . urlencode('Invalid file type. Only JPG, JPEG, PNG, or WEBP are allowed.'));
    exit;
}

// Cek ukuran file (misal: maks 5MB)
$max_file_size = 5 * 1024 * 1024; // 5MB
if ($file_size > $max_file_size) {
    header('Location: /VALORANT/public/user/add_map.php?error=' . urlencode('File size is too large. Max 5MB allowed.'));
    exit;
}

// --- 4. Proses Pindah File ---
// Buat nama file bersih berdasarkan nama map
$clean_map_name = strtolower(str_replace(' ', '_', $name_map));
$new_file_name = $clean_map_name . '.' . $file_ext;
$upload_destination = __DIR__ . '/../public/images/maps/' . $new_file_name;

// Pastikan folder ../public/images/maps/ ada (sesuai struktur folder Anda)
if (!is_dir(__DIR__ . '/../public/images/maps/')) {
    mkdir(__DIR__ . '/../public/images/maps/', 0755, true);
}

if (!move_uploaded_file($file_tmp_name, $upload_destination)) {
    header('Location: /VALORANT/public/user/add_map.php?error=' . urlencode('Failed to move uploaded file. Check folder permissions.'));
    exit;
}

// --- 5. Masukkan ke Database ---
$stmt = mysqli_prepare($conn, "INSERT INTO maps (name_map, desc_map, image_map) VALUES (?, ?, ?)");
// Tipe data 'sss' (string, string, string)
mysqli_stmt_bind_param($stmt, "sss", $name_map, $desc_map, $new_file_name);

if (mysqli_stmt_execute($stmt)) {
    header('Location: /VALORANT/public/user/maps.php?success=' . urlencode('Map added successfully!'));
} else {
    // Jika database gagal, hapus file yang sudah di-upload
    unlink($upload_destination);
    header('Location: /VALORANT/public/user/add_map.php?error=' . urlencode('Failed to add map to database. Please try again.'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;