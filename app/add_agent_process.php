<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "You are not authorized to perform this action.";
    exit;
}

require_once __DIR__ . '/../config/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /VALORANT/public/user/add_agent.php');
    exit;
}


$agent_name = trim($_POST['agent_name']);
$country_agent = trim($_POST['country_agent']);
$role_agent = trim($_POST['role_agent']);
$allowed_roles = ['Duelist', 'Initiator', 'Controller', 'Sentinel'];


if (empty($agent_name) || empty($country_agent) || empty($role_agent)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('All fields are required.'));
    exit;
}

if (!in_array($role_agent, $allowed_roles)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Invalid role selected.'));
    exit;
}


if (!isset($_FILES['agent_image']) || $_FILES['agent_image']['error'] !== UPLOAD_ERR_OK) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Image upload failed or no image selected.'));
    exit;
}

$file = $_FILES['agent_image'];
$file_tmp_name = $file['tmp_name'];
$file_size = $file['size'];

$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($file_ext, $allowed_ext)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Invalid file type. Only JPG, JPEG, PNG, or WEBP are allowed.'));
    exit;
}


$max_file_size = 5 * 1024 * 1024; 
if ($file_size > $max_file_size) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('File size is too large. Max 5MB allowed.'));
    exit;
}


$clean_agent_name = strtolower(str_replace(' ', '_', $agent_name));


$new_file_name = $clean_agent_name . '.' . $file_ext;
$upload_destination = __DIR__ . '/../public/images/agents/' . $new_file_name;


if (!is_dir(__DIR__ . '/../../public/images/agents/')) {
    mkdir(__DIR__ . '/../../public/images/agents/', 0755, true);
}


if (!move_uploaded_file($file_tmp_name, $upload_destination)) {
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Failed to move uploaded file. Check folder permissions.'));
    exit;
}


$stmt = mysqli_prepare($conn, "INSERT INTO agents (agent_name, country_agent, role_agent, agent_image) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $agent_name, $country_agent, $role_agent, $new_file_name);

if (mysqli_stmt_execute($stmt)) {
    header('Location: /VALORANT/public/user/agents.php?success=' . urlencode('Agent added successfully!'));
} else {
  
    unlink($upload_destination);
    header('Location: /VALORANT/public/user/add_agent.php?error=' . urlencode('Failed to add agent to database. Please try again.'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;