<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../../config/config.php';
// Ambil data agents dari database
$agents = [];
$sql = "SELECT * FROM agents";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $agents[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VALORANT - Agents</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @font-face {
            font-family: 'ValorantFont';
            src: url('../font/Valorant-Font.ttf') format('truetype');
            font-display: swap;
        }
        .valorant-text {
            font-family: 'ValorantFont', sans-serif;
            letter-spacing: 0px;
        }
        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="bg-[#0F1923] text-[#ECE8E1] min-h-screen">
    <?php include __DIR__ . '/../layout/_navbar.php'; ?>
    <main>
        <section class="relative min-h-screen">
            <img src="../images/background.png" alt="background pattern" class="absolute inset-0 w-full h-full object-cover object-center opacity-10 z-0">
            <h1 class="valorant-text text-8xl font-bold text-white vertical-text tracking-widest drop-shadow-2xl absolute top-1/2 -translate-y-1/2 left-20 z-20">
                AGENTS
            </h1>
            <div class="w-full min-h-screen flex flex-col justify-center items-center px-8 py-16 z-10 relative pl-72">
                <div class="w-full max-w-7xl flex justify-between items-center mb-8">
                    <div class="flex gap-2 relative z-10">
                        <div class="w-6 h-2 bg-white rounded-full"></div>
                        <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                        <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                        <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/VALORANT/public/user/add_agent.php" class="bg-[#FF4655] hover:bg-[#FF4655]/90 text-white font-bold py-2 px-6 rounded transition-all duration-300 text-sm uppercase tracking-wider">
                            + Add New Agent
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex gap-8 items-center w-full max-w-7xl overflow-x-auto scrollbar-hide pb-4 z-10">
                    <?php if (empty($agents)): ?>
                        <div class="text-white text-lg">No agents found in database.</div>
                    <?php else: ?>
                        <?php foreach($agents as $agent): ?>
                        <div class="group w-72 h-[520px] rounded-2xl overflow-hidden flex-shrink-0
                                    bg-zinc-900/70 backdrop-blur-md border border-zinc-700
                                    transition-all duration-300 ease-out
                                    hover:-translate-y-2 hover:border-red-500/70 hover:shadow-2xl hover:shadow-red-500/20 relative">
                            <div class="absolute top-6 left-6 z-20">
                                <span class="text-white text-xs font-bold bg-red-600 px-3 py-1.5 rounded-full shadow-lg">
                                    <?= $agent['country_agent'] ?? 'Unknown Origin' ?>
                                </span>
                            </div>
                            <div class="h-4/5 relative overflow-hidden">
                            <img src="../images/agents/<?= $agent['agent_image'] ?? 'agent.png' ?>" alt="..." ...>
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-black/30 to-transparent"></div>
                            </div>
                            <div class="h-1/5 bg-zinc-950 p-6 flex flex-col justify-center relative">
                                <h3 class="valorant-text text-3xl font-bold text-white mb-3 tracking-wider"><?= $agent['agent_name'] ?? 'No Name' ?></h3>
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold bg-zinc-800">Q</div>
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold bg-zinc-800">E</div>
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold bg-zinc-800">C</div>
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold bg-zinc-800">X</div>
                                </div>
                                <div class="absolute top-6 right-6">
                                    <span class="text-red-400 text-xs font-bold bg-red-500/10 px-3 py-1 rounded-full uppercase">
                                        <?= $agent['role_agent'] ?? 'No Role' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>