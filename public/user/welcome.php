<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            font-size: 150px;
        }
        
        .hero-text {
            font-family: 'Tungsten', sans-serif;
            letter-spacing: 2px;
        }
    </style>
</head>


<body>
    <main>
        <section class="h-screen bg-cover bg-center flex flex-col items-center justify-center text-white" style="background-image: url('../images/login2.jpg');">
            <h1 class=" valorant-text mb-10 text-center drop-shadow-lg tracking-widest">
                VALORANT
            </h1>
            <button  class="px-12 py-4 bg-[#ECE8E1] border-4 border-[#ECE8E1] text-[#FF4655] font-bold text-xl tracking-wider hover:bg-[#ECE8E1]/70 hover:text-[#FF4655]/90 transition-all duration-300 rounded-none shadow-[0_0_0_2px_#ECE8E1_inset]">
                <a href="../user/homepage.php">Enter</a>
            </button>

        </section>
    </main>
    
</body>
</html>