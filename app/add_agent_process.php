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
    header('Location: /VALORANT/public/user/add_agent.php');
    exit;
}

// --- 1. Ambil Data Teks ---
$agent_name = trim($_POST['agent_name']);
$country_agent = trim($_POST['country_agent']);
$role_agent = trim($_POST['role_agent']);
$allowed_roles = ['Duelist', 'Initiator', 'Controller', 'Sentinel'];

// --- 2. Validasi Data Teks ---
if (empty($agent_name) || empty($country_agent) || empty($role_agent)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('All fields are required.'));
    exit;
}

if (!in_array($role_agent, $allowed_roles)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Invalid role selected.'));
    exit;
}

// --- 3. Validasi File Upload ---
if (!isset($_FILES['agent_image']) || $_FILES['agent_image']['error'] !== UPLOAD_ERR_OK) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Image upload failed or no image selected.'));
    exit;
}

$file = $_FILES['agent_image'];
$file_tmp_name = $file['tmp_name'];
$file_size = $file['size'];
// $file_error = $file['error']; // Variabel ini tidak terpakai

// Cek ekstensi file
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($file_ext, $allowed_ext)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Invalid file type. Only JPG, JPEG, PNG, or WEBP are allowed.'));
    exit;
}

// Cek ukuran file (misal: maks 5MB)
$max_file_size = 5 * 1024 * 1024; // 5MB
if ($file_size > $max_file_size) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('File size is too large. Max 5MB allowed.'));
    exit;
}

// --- 4. Proses Pindah File (SESUAI PERMINTAAN ANDA) ---
// 1. Bersihkan nama agen (ubah "Agent ABC" jadi "agent_abc")
$clean_agent_name = strtolower(str_replace(' ', '_', $agent_name));

// 2. Buat nama file baru (misal: "agent_abc.png")
$new_file_name = $clean_agent_name . '.' . $file_ext;
$upload_destination = __DIR__ . '/../public/images/agents/' . $new_file_name;

// Pastikan folder ../../public/images/agents/ ada dan bisa ditulisi
if (!is_dir(__DIR__ . '/../../public/images/agents/')) {
    mkdir(__DIR__ . '/../../public/images/agents/', 0755, true);
}

// !! INI ADALAH BAGIAN YANG DIPERBAIKI !!
// Memindahkan file yang diupload ke lokasi tujuan
if (!move_uploaded_file($file_tmp_name, $upload_destination)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Failed to move uploaded file. Check folder permissions.'));
    exit;
}

// BLOK KODE 'poster_file' YANG SALAH TELAH DIHAPUS DARI SINI

// --- 5. Masukkan ke Database ---
// Nama file yang disimpan di DB adalah nama baru (misal: "agent_abc.png")
$stmt = mysqli_prepare($conn, "INSERT INTO agents (agent_name, country_agent, role_agent, agent_image) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $agent_name, $country_agent, $role_agent, $new_file_name);

if (mysqli_stmt_execute($stmt)) {
    header('Location: /VALORANT/public/user/agents.php?success=' . urlencode('Agent added successfully!'));
} else {
    // Jika database gagal, hapus file yang sudah di-upload
    unlink($upload_destination);
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Failed to add agent to database. Please try again.'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;