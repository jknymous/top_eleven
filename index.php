<?php
session_start();

require_once 'config.php';
require_once 'functions.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$errors = [];
$success = '';
$oldData = $_POST;

if ($page === 'tambah-pemain' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validatePlayerData($_POST);

    if (!$errors) {
        // sanitize inputs before DB insert
        $data = [
            'nama' => trim($_POST['nama']),
            'umur' => intval($_POST['umur']),
            'ovr' => intval($_POST['ovr']),
            'posisi' => $_POST['posisi'],
            'keahlian' => intval($_POST['keahlian']),
            'gaya_main' => $_POST['gaya_main'],
            'kelas' => intval($_POST['kelas'])
        ];

        insertPlayer($pdo, $data);
        $success = 'Pemain berhasil ditambahkan.';
        
        $oldData = [];
    }
}

if ($page === 'pemain') {
    $players = fetchPlayers($pdo);
}

$currentPage = $page;

include 'header.php';

switch ($page) {
    case 'dashboard':
        include 'dashboard.php';
        break;

    case 'tambah-pemain':
        include 'tambah-pemain.php';
        break;

    case 'pemain':
        include 'pemain.php';
        break;

    case 'rekomen-pemain':
        include 'rekomen-pemain.php';
        break;

    case 'latihan-militer':
        include 'latihan-militer.php';
        break;

    default:
        echo '<h1 class="text-4xl font-extrabold text-blue-700 mb-6 select-none">Halaman tidak ditemukan</h1>';
        echo '<p>Halaman yang Anda minta tidak tersedia.</p>';
        break;
}

include 'footer.php';
