<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../../config/config.php';
// Ambil data maps dari database
$maps = [];
$sql = "SELECT * FROM maps";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $maps[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VALORANT - Maps</title>
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
        /* Style tambahan untuk transisi modal */
        #deleteModal.hidden {
            display: none;
        }
        #modalOverlay {
            transition: opacity 300ms ease-in-out;
        }
        #modalContent {
            transition: all 300ms ease-in-out;
        }
    </style>
</head>
<body class="bg-[#0F1923] text-[#ECE8E1] min-h-screen">
    <?php include __DIR__ . '/../layout/_navbar.php'; ?>
    
    <main class="relative min-h-screen">
        <img src="../images/background.png" alt="background pattern" class="absolute inset-0 w-full h-full object-cover object-center opacity-10 z-0">
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pt-32">
            
            <div class="flex justify-between items-center mb-8">
                <h1 class="valorant-text text-6xl text-white drop-shadow-lg">MAPS</h1>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/VALORANT/public/user/add_map.php" class="bg-[#FF4655] hover:bg-[#FF4655]/90 text-white font-bold py-2 px-6 rounded transition-all duration-300 text-sm uppercase tracking-wider">
                        + Add New Map
                    </a>
                <?php endif; ?>
            </div>

            <?php if (empty($maps)): ?>
                <div class="text-zinc-400 text-lg text-center py-16">
                    No maps found in the database.
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <br>Click "+ Add New Map" to get started.
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach($maps as $map): ?>
                    <div class="group rounded-2xl overflow-hidden
                                bg-zinc-900/70 backdrop-blur-md border border-zinc-700
                                transition-all duration-300 ease-out
                                hover:-translate-y-2 hover:border-red-500/70 hover:shadow-2xl hover:shadow-red-500/20 relative">
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="/VALORANT/public/user/edit_map.php?id=<?= $map['id_map'] ?>" 
                               class="absolute top-4 right-14 z-30 bg-zinc-600/80 text-white w-8 h-8 rounded-full 
                                      flex items-center justify-center backdrop-blur-md
                                      opacity-0 group-hover:opacity-100 transition-all duration-300
                                      hover:bg-zinc-500"
                               title="Edit Map">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM5 12V7.172l4-4L13.828 8 9 12.828V15H6.172L5 13.828V12zM15 5l-1-1-4 4v2.828l4-4L15 5zM5 16h10v2H5v-2z"></path></svg>
                            </a>
                            <a href="/VALORANT/app/delete_map.php?id=<?= $map['id_map'] ?>" 
                               class="delete-link absolute top-4 right-4 z-30 bg-red-600/80 text-white w-8 h-8 rounded-full 
                                      flex items-center justify-center font-bold text-lg backdrop-blur-md
                                      opacity-0 group-hover:opacity-100 transition-all duration-300
                                      hover:bg-red-500 cursor-pointer"
                               title="Delete Map">
                                X
                            </a>
                        <?php endif; ?>
                        
                        <div class="h-48 relative overflow-hidden">
                            <img src="../images/maps/<?= $map['image_map'] ?? 'default_map.png' ?>" 
                                 alt="<?= $map['name_map'] ?? 'Map' ?>" 
                                 class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-black/30 to-transparent"></div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="valorant-text text-3xl font-bold text-white mb-3 tracking-wider"><?= $map['name_map'] ?? 'No Name' ?></h3>
                            <p class="text-zinc-400 text-sm h-20 overflow-y-auto">
                                <?= $map['desc_map'] ?? 'No description available.' ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300">
        <div id="modalOverlay" class="absolute inset-0 bg-black/70 backdrop-blur-sm opacity-0"></div>
        <div id="modalContent" class="relative w-full max-w-md p-8 bg-zinc-900 border border-zinc-700 rounded-lg shadow-2xl shadow-red-500/20
                                      transform scale-95 opacity-0">
            <h2 class="valorant-text text-3xl text-white text-center mb-4">CONFIRM DELETION</h2>
            <p class="text-center text-zinc-300 mb-8">
                Are you sure you want to delete this map?
                <br>
                <strong class="text-red-400">This action cannot be undone.</strong>
            </p>
            <div class="flex justify-center gap-4">
                <button id="cancelDelete" class="valorant-text text-sm uppercase tracking-wider text-zinc-400 bg-zinc-700 hover:bg-zinc-600 px-6 py-2 rounded-sm transition-colors duration-200" style="font-size: 14px;">
                    Cancel
                </button>
                <a id="confirmDelete" href="#" class="valorant-text text-sm uppercase tracking-wider text-white bg-red-600 hover:bg-red-500 px-6 py-2 rounded-sm transition-colors duration-200" style="font-size: 14px;">
                    Delete
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteModal');
            const modalOverlay = document.getElementById('modalOverlay');
            const modalContent = document.getElementById('modalContent');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            const deleteLinks = document.querySelectorAll('.delete-link');

            const showModal = (href) => {
                confirmDelete.href = href;
                deleteModal.classList.remove('hidden');
                setTimeout(() => {
                    modalOverlay.classList.add('opacity-100');
                    modalContent.classList.add('scale-100', 'opacity-100');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                }, 10);
            };

            const hideModal = () => {
                modalOverlay.classList.remove('opacity-100');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    deleteModal.classList.add('hidden');
                    confirmDelete.href = '#'; 
                }, 300); 
            };

            deleteLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault(); 
                    const deleteUrl = link.getAttribute('href');
                    showModal(deleteUrl);
                });
            });

            cancelDelete.addEventListener('click', hideModal);
            modalOverlay.addEventListener('click', hideModal);
        });
    </script>
</body>
</html>