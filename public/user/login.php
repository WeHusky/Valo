<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @font-face {
            font-family: 'ValorantFont';
            src: url('/VALORANT/public/font/Valorant-Font.ttf') format('truetype');
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
        <section class="h-screen bg-cover bg-center flex flex-col items-center justify-center text-white" style="background-image: url('/VALORANT/public/images/login3.jpg');">
            <h1 class=" valorant-text mb-10 text-center drop-shadow-lg tracking-widest">
                VALORANT
            </h1>
            
            <button id="loginButton" class="px-12 py-4 bg-[#ECE8E1] border-4 border-[#ECE8E1] text-[#FF4655] font-bold text-xl tracking-wider hover:bg-[#ECE8E1]/70 hover:text-[#FF4655]/90 transition-all duration-300 rounded-none shadow-[0_0_0_2px_#ECE8E1_inset]">
                LOGIN
            </button>
        </section>


        <div id="loginModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
            
            <div id="modalOverlay" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            
            <div class="relative w-full max-w-md p-8 bg-zinc-900/80 backdrop-blur-lg border border-zinc-700 rounded-lg shadow-2xl shadow-red-500/20">
                
                <button id="closeModalButton" class="absolute top-4 right-4 text-zinc-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <h2 class="valorant-text text-5xl text-white text-center mb-6" style="font-size: 50px;">
                    SIGN IN
                </h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm p-3 rounded-md mb-4">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm p-3 rounded-md mb-4">
                        <?= htmlspecialchars($_GET['success']) ?>
                    </div>
                <?php endif; ?>

                <form action="/VALORANT/app/login_process.php" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider">Email</label>
                            <input type="email" id="email" name="email"
                                   class="mt-1 block w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-md text-white
                                          focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider">Password</label>
                            <input type="password" id="password" name="password"
                                   class="mt-1 block w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-md text-white
                                          focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                        </div>
                        
                        <button type="submit"
                                class="w-full mt-4 px-12 py-3 bg-[#FF4655] text-white font-bold text-lg tracking-wider
                                       hover:bg-red-600 transition-all duration-300 rounded-md valorant-text" style="font-size: 20px;">
                            SIGN IN
                        </button>
                    </div>
                </form>
                </div>
        </div>

    </main>
    
    <script>
        // Ambil elemen-elemen yang dibutuhkan
        const loginButton = document.getElementById('loginButton');
        const loginModal = document.getElementById('loginModal');
        const closeModalButton = document.getElementById('closeModalButton');
        const modalOverlay = document.getElementById('modalOverlay');

        // Fungsi untuk menampilkan modal
        function openModal() {
            loginModal.classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal
        function closeModal() {
            loginModal.classList.add('hidden');
        }

        // Tambahkan 'event listener'
        // Saat tombol login diklik, panggil fungsi openModal
        loginButton.addEventListener('click', openModal);

        // Saat tombol close (X) diklik, panggil fungsi closeModal
        closeModalButton.addEventListener('click', closeModal);

        // Saat overlay (background gelap) diklik, panggil fungsi closeModal
        modalOverlay.addEventListener('click', closeModal);

        // OTOMATIS TAMPILKAN MODAL JIKA ADA PESAN ERROR/SUCCESS
        <?php if (isset($_GET['error']) || isset($_GET['success'])): ?>
            openModal();
        <?php endif; ?>
    </script>
    
</body>
</html>