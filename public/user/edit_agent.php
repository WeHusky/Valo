<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../../config/config.php';

// Keamanan: Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: /VALORANT/public/user/login.php?error=' . urlencode('You must be logged in.'));
    exit;
}

// 1. Ambil ID dari URL dan pastikan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /VALORANT/public/user/agents.php?error=' . urlencode('Invalid agent ID.'));
    exit;
}
$agent_id = (int)$_GET['id'];

// 2. Ambil data agen saat ini dari database
$stmt = mysqli_prepare($conn, "SELECT * FROM agents WHERE id_agent = ?");
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$agent = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika agen tidak ditemukan
if (!$agent) {
    header('Location: /VALORANT/public/user/agents.php?error=' . urlencode('Agent not found.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VALORANT - Edit Agent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @font-face {
            font-family: 'ValorantFont';
            src: url('/VALORANT/public/font/Valorant-Font.ttf') format('truetype');
            font-display: swap;
        }
        .valorant-text {
            font-family: 'ValorantFont', sans-serif;
        }
    </style>
</head>
<body class="bg-[#0F1923] text-[#ECE8E1] min_h-screen">
    <?php include __DIR__ . '/../layout/_navbar.php'; ?>
    
    <main class="pt-32">
        <section class="max_w-2xl mx-auto p-8 bg-zinc-900/80 backdrop-blur-lg border border-zinc-700 rounded-lg shadow-2xl shadow-red-500/20">
            <h1 class="valorant-text text-4xl text-white text-center mb-8">EDIT AGENT</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm p-3 rounded-md mb-4">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>
            
            <form action="/VALORANT/app/edit_agent_process.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <input type="hidden" name="id_agent" value="<?= $agent['id_agent'] ?>">
                <input type="hidden" name="old_image_name" value="<?= $agent['agent_image'] ?>">
                
                <div>
                    <label for="agent_name" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider">Agent Name</label>
                    <input type="text" id="agent_name" name="agent_name" required 
                           class="mt-1 block w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 transition-all"
                           value="<?= htmlspecialchars($agent['agent_name']) ?>">
                </div>
                <div>
                    <label for="country_agent" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider">Country</label>
                    <input type="text" id="country_agent" name="country_agent" required 
                           class="mt-1 block w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 transition-all"
                           value="<?= htmlspecialchars($agent['country_agent']) ?>">
                </div>
                <div>
                    <label for="role_agent" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider">Role</label>
                    <select id="role_agent" name="role_agent" required class="mt-1 block w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                        <option value="Duelist" <?= ($agent['role_agent'] == 'Duelist') ? 'selected' : '' ?>>Duelist</option>
                        <option value="Initiator" <?= ($agent['role_agent'] == 'Initiator') ? 'selected' : '' ?>>Initiator</option>
                        <option value="Controller" <?= ($agent['role_agent'] == 'Controller') ? 'selected' : '' ?>>Controller</option>
                        <option value="Sentinel" <?= ($agent['role_agent'] == 'Sentinel') ? 'selected' : '' ?>>Sentinel</option>
                    </select>
                </div>
                <div>
                    <label for="agent_image" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider">Agent Image</label>
                    <p class="text-xs text-zinc-400 mt-1">Current: <?= htmlspecialchars($agent['agent_image']) ?>. Leave blank to keep.</p>
                    <input type="file" id="agent_image" name="agent_image" 
                           class="mt-2 block w-full text-sm text-zinc-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-zinc-700 file:text-zinc-200
                                  hover:file:bg-zinc-600
                                  focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                </div>
                <button type="submit" class="w-full mt-4 px-12 py-3 bg-[#FF4655] text-white font-bold text-lg tracking-wider hover:bg-red-600 transition-all duration-300 rounded-md valorant-text" style="font-size: 18px;">
                    UPDATE AGENT
                </button>
            </form>
        </section>
    </main>
</body>
</html>