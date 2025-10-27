<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="bg-[#0F1923]/80 backdrop-blur-md fixed top-0 left-0 right-0 z-50 valorant-text px-12 py-4">
    <div class="flex items-center justify-between gap-4">
        
        <div class="logo flex items-center gap-2.5">
            <a href="/VALORANT/public/user/homepage.php">
                <img src="/VALORANT/public/images/V.png" alt="Valo Logo" class="h-12 w-12">
            </a>
        </div>

        <ul class="nav-links flex list-none items-center gap-10 text-sm uppercase tracking-wider">
            <li>
                <a href="/VALORANT/public/user/agents.php" class="text-white opacity-70 hover:opacity-100 hover:text-white transition-all duration-300">Agents</a>
            </li>
            <li>
                <a href="#" class="text-white opacity-70 hover:opacity-100 hover:text-white transition-all duration-300">Maps</a>
            </li>
            <li>
                <a href="#" class="text-white opacity-70 hover:opacity-100 hover:text-white transition-all duration-300">Weapons</a>
            </li>
            <li>
                <a href="#" class="text-white opacity-70 hover:opacity-100 hover:text-white transition-all duration-300">Competitive</a>
            </li>
            

            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                <!-- Tampilan jika pengguna sudah login -->
                <li>
                    <a href="/VALORANT/public/user/add_agent.php" class="text-white opacity-70 hover:opacity-100 hover:text-white transition-all duration-300">Add Agent</a>
                </li>
                <li class="text-white opacity-70 text">
                    Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
                </li>
                <li>
                    <a href="/VALORANT/app/logout.php" class="font-bold text-[#FF4655] border-2 border-[#FF4655] px-6 py-2 rounded-sm hover:bg-[#FF4655] hover:text-[#0F1923] transition-all duration-300">
                        Logout
                    </a>
                </li>
            <?php else: ?>
                <!-- Tampilan jika pengguna belum login -->
                <li>
                    <a href="/VALORANT/public/user/login.php" class="font-bold text-[#FF4655] border-2 border-[#FF4655] px-6 py-2 rounded-sm hover:bg-[#FF4655] hover:text-[#0F1923] transition-all duration-300">
                        Login
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>