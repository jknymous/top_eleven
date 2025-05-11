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

    // Check current player count before adding new
    $stmtCount = $pdo->query('SELECT COUNT(*) FROM players');
    $currentCount = (int)$stmtCount->fetchColumn();

    if ($currentCount >= 28) {
        // Limit reached, do not add player
        $errors[] = 'Batas maksimum pemain (28) telah tercapai. Tidak dapat menambahkan pemain baru.';
    } else {
        if (!$errors) {
            // Sanitize inputs before DB insert
            $data = [
                'nama' => trim($_POST['nama']),
                'umur' => intval($_POST['umur']),
                'ovr' => intval($_POST['ovr']),
                'posisi' => $_POST['posisi'],
                'keahlian' => intval($_POST['keahlian']),
                'gaya_main' => $_POST['gaya_main']
            ];

            insertPlayer($pdo, $data);
            $success = 'Pemain berhasil ditambahkan.';
            $oldData = [];
        }
    }
}

if ($page === 'pemain') {
    // Handle POST actions: update or delete
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'update') {
                // Collect, validate update data
                $id = intval($_POST['id'] ?? 0);
                $nama = trim($_POST['nama'] ?? '');
                $umur = intval($_POST['umur'] ?? 0);
                $ovr = intval($_POST['ovr'] ?? 0);
                $posisi = $_POST['posisi'] ?? '';
                $keahlian = intval($_POST['keahlian'] ?? -1);
                $gaya_main = $_POST['gaya_main'] ?? '';

                $input = compact('nama', 'umur', 'ovr', 'posisi', 'keahlian', 'gaya_main');
                $errors = validatePlayerData($input);

                if (!$errors && $id > 0) {
                    $stmt = $pdo->prepare('UPDATE players SET nama=?, umur=?, ovr=?, posisi=?, keahlian=?, gaya_main=? WHERE id=?');
                    $stmt->execute([$nama, $umur, $ovr, $posisi, $keahlian, $gaya_main, $id]);
                    $success = 'Pemain berhasil diperbarui.';
                }

            } elseif ($action === 'delete') {
                $id = intval($_POST['id'] ?? 0);
                if ($id > 0) {
                    $stmt = $pdo->prepare('DELETE FROM players WHERE id=?');
                    $stmt->execute([$id]);
                    $success = 'Pemain berhasil dihapus.';
                }
            }
        }
    }
    // Fetch players
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
