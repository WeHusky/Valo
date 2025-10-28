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


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /VALORANT/public/user/agents.php?error=' . urlencode('Invalid agent ID.'));
    exit;
}
$agent_id = (int)$_GET['id'];



$stmt_find = mysqli_prepare($conn, "SELECT agent_image FROM agents WHERE id_agent = ?");
mysqli_stmt_bind_param($stmt_find, "i", $agent_id);
mysqli_stmt_execute($stmt_find);
$result = mysqli_stmt_get_result($stmt_find);
$agent = mysqli_fetch_assoc($result);
$image_filename = null;

if ($agent && !empty($agent['agent_image'])) {
    $image_filename = $agent['agent_image'];
}
mysqli_stmt_close($stmt_find);


$stmt_delete = mysqli_prepare($conn, "DELETE FROM agents WHERE id_agent = ?");
mysqli_stmt_bind_param($stmt_delete, "i", $agent_id);

if (mysqli_stmt_execute($stmt_delete)) {

    if ($image_filename) {
        $file_path = __DIR__ . '/../public/images/agents/' . $image_filename;
        
        // Cek jika file ada, lalu hapus
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    

    header('Location: /VALORANT/public/user/agents.php?success=' . urlencode('Agent deleted successfully.'));
} else {
    header('Location: /VALORANT/public/user/agents.php?error=' . urlencode('Failed to delete agent.'));
}

mysqli_stmt_close($stmt_delete);
mysqli_close($conn);
exit;