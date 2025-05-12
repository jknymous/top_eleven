<?php
// header.php - begins the HTML and sidebar navigation
// Usage: include 'header.php'; pass $currentPage string
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Top Eleven Management</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --blue-light: #dbeafe;
            --blue-base: #2563eb;
            --blue-dark: #1e40af;
        }
        body {
            background-color: white;
            color: var(--blue-dark);
        }
        main::-webkit-scrollbar {
            width: 8px;
        }
        main::-webkit-scrollbar-thumb {
            background-color: var(--blue-light);
            border-radius: 4px;
        }
    </style>
</head>
<body class="flex h-screen font-sans">

    <!-- Sidebar -->
    <aside class="flex flex-col bg-white border-r border-gray-200 w-72 shadow-lg select-none">
        <div class="flex items-center justify-center h-20 border-b border-gray-200">
            <!-- Logo -->
            <!-- <div class="text-3xl font-extrabold tracking-tight text-blue-700">FootballPro</div> -->
            <img src='top.png' class="w-auto h-16">
        </div>

        <nav class="flex-1 px-6 py-8 flex flex-col justify-between">
            <ul class="space-y-6">
                <?php 
                    $menuItems = [
                        'dashboard' => ['label'=>'Dashboard', 'icon'=> '<path d="M3 10h3v11H3v-11zM9 14h3v7H9v-7zM15 4h3v17h-3V4z"/>'],
                        'tambah-pemain' => ['label'=>'Tambah Pemain', 'icon'=> '<path d="M12 4v16M8 12h8"/>'],
                        'pemain' => ['label'=>'Pemain', 'icon'=> '<path d="M9 17v-6h13M9 7h10m-6 10l4-4-4-4"/>'],
                        'rekomen-pemain' => ['label'=>'Rekomen Pemain', 'icon'=> '<path d="M9 17v-6h13M9 7h10m-6 10l4-4-4-4"/>'],
                        'latihan-militer' => ['label'=>'Latihan Militer', 'icon'=> '<path d="M3 10h2l1 6h12l1-6h2M7 10V6a5 5 0 1110 0v4M5 10h14"/>'],
                    ];
                    foreach ($menuItems as $key => $item):
                        $active = ($currentPage === $key) ? 'bg-blue-100 text-blue-600' : 'text-blue-700 hover:text-blue-600 hover:bg-blue-100';
                ?>
                <li>
                    <a href="?page=<?= $key ?>" class="flex items-center space-x-3 rounded-lg px-3 py-2 transition-colors duration-200 <?= $active ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $item['icon'] ?></svg>
                        <span class="font-semibold text-lg"><?= $item['label'] ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <a href="#" class="flex items-center space-x-3 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg px-3 py-2 transition-colors duration-200 mt-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                </svg>
                <span class="font-semibold text-lg">Keluar</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-10 bg-blue-50 overflow-auto">
