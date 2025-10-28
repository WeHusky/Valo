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
    header('Location: /VALORANT/public/user/agents.php');
    exit;
}


$id_agent = (int)$_POST['id_agent'];
$agent_name = trim($_POST['agent_name']);
$country_agent = trim($_POST['country_agent']);
$role_agent = trim($_POST['role_agent']);
$old_image_name = trim($_POST['old_image_name']);

$allowed_roles = ['Duelist', 'Initiator', 'Controller', 'Sentinel'];


if (empty($id_agent) || empty($agent_name) || empty($country_agent) || empty($role_agent)) {
    header('Location: /VALORANT/public/user/edit_agent.php?id=' . $id_agent . '&error=' . urlencode('All fields are required.'));
    exit;
}
if (!in_array($role_agent, $allowed_roles)) {
    header('Location: /VALORANT/public/user/edit_agent.php?id=' . $id_agent . '&error=' . urlencode('Invalid role selected.'));
    exit;
}

$new_file_name = $old_image_name; 


if (isset($_FILES['agent_image']) && $_FILES['agent_image']['error'] === UPLOAD_ERR_OK) {
    
    $file = $_FILES['agent_image'];
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];


    if (!in_array($file_ext, $allowed_ext)) {
        header('Location: /VALORANT/public/user/edit_agent.php?id=' . $id_agent . '&error=' . urlencode('Invalid file type.'));
        exit;
    }
    if ($file_size > 5 * 1024 * 1024) { 
        header('Location: /VALORANT/public/user/edit_agent.php?id=' . $id_agent . '&error=' . urlencode('File size is too large. Max 5MB.'));
        exit;
    }
    

    $clean_agent_name = strtolower(str_replace(' ', '_', $agent_name));
    $new_file_name = $clean_agent_name . '.' . $file_ext;
    $upload_destination = __DIR__ . '/../public/images/agents/' . $new_file_name;


    if (move_uploaded_file($file_tmp_name, $upload_destination)) {
  
        if ($old_image_name && $old_image_name != $new_file_name) {
            $old_file_path = __DIR__ . '/../public/images/agents/' . $old_image_name;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }
    } else {
        header('Location: /VALORANT/public/user/edit_agent.php?id=' . $id_agent . '&error=' . urlencode('Failed to move uploaded file.'));
        exit;
    }
}


$stmt = mysqli_prepare($conn, "UPDATE agents SET agent_name = ?, country_agent = ?, role_agent = ?, agent_image = ? WHERE id_agent = ?");
mysqli_stmt_bind_param($stmt, "ssssi", $agent_name, $country_agent, $role_agent, $new_file_name, $id_agent);

if (mysqli_stmt_execute($stmt)) {
    header('Location: /VALORANT/public/user/agents.php?success=' . urlencode('Agent updated successfully!'));
} else {
    header('Location: /VALORANT/public/user/edit_agent.php?id=' . $id_agent . '&error=' . urlencode('Failed to update agent.'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;