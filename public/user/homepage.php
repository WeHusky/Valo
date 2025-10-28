<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-[#0F1923] text-[#ECE8E1] min-h-screen">
<?php include __DIR__ . '/../layout/_navbar.php'; ?>
    <main>
        <section class="relative min-h-screen flex" >
            <div class="w-1/3 bg-[#0F1923] flex flex-col justify-center px-12 py-16">
                <div class="mb-8">
                <h1 class="text-6xl font-bold mb-4 leading-tight">WE ARE VALORANT</h1>
                <h2 class="text-xl font-semibold mb-6 text-[#ECE8E1]">DEFY THE LIMITS.</h2>
                <p class="text-sm leading-relaxed text-gray-300">
                Blend your style and experience on a global, competitive stage. You have 13 rounds 
                to attack and defend your side using sharp gunplay and tactical abilities. And, with one 
                life per round, you'll need to think faster than your opponent if you want to survive. 
                Take on foes across Competitive and Unranked modes as well as Deathmatch and Spike Rush.
                </p>
                </div>
            </div>
                <div class="w-2/3 relative">
                    <img src="../images/home.png" alt="Valorant Agents" class="w-full h-screen object-cover object-center">
                </div>
        </section>
        <section class="relative min-h-screen flex">
            <div class="w-2/3 relative">
                <img src="../images/agent.png" alt="Valorant Agents" class="w-full h-screen object-cover object-center">
            </div>
            <div class="w-1/3 bg-[#0F1923] flex flex-col justify-center px-12 py-16">
                <div class="mb-8">
                <h1 class="text-6xl font-bold mb-4 leading-tight text-white">YOUR AGENTS</h1>
                <h2 class="text-xl font-semibold mb-6 text-[#ECE8E1]">CREATIVE IS YOUR GREATEST WEAPON.</h2>
                <p class="text-sm leading-relaxed text-gray-300 mb-8">
                More than guns and bullets, you'll choose an Agent armed with adaptive, swift, and lethal abilities 
                that create opportunities to let your gunplay shine. No two Agents play alike, just as no two 
                highlight reels will look the same.
                </p>
                <button  class="bg-[#FF4655] hover:bg-[#FF4655]/90 text-white font-bold py-3 px-8 rounded transition-all duration-300"><a href="../user/agents.php">View All Agents</a>
                </button>
                </div>
            </div>        
        </section>
        <section class="relative min-h-screen bg-cover bg-center bg-no-repeat flex items-center" style="background-image: url('../images/maps.png');">
            <div class="relative z-10 w-1/3 px-12 py-16">
                <div class="mb-8">
                <h1 class="text-6xl font-bold mb-4 leading-tight text-white">
                    YOUR MAPS
                </h1>
                <h2 class="text-xl font-semibold mb-6 text-[#ECE8E1]">FIGHT AROUND THE WORLD.</h2>
                <p class="text-sm leading-relaxed text-gray-300 mb-8">
                    Each map is a playground to showcase your creative thinking. Purpose-built for team strategies, 
                    spectacular plays, and clutch moments. Make the play others will imitate for years to come.
                </p>
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-8 rounded transition-all duration-300">
                    View All Maps
                </button>
                </div>
            </div>
        </section>
    </main>
</body>
</html>