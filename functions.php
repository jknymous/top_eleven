<?php
// functions.php - reusable functions

function fetchPlayers(PDO $pdo): array {
    $stmt = $pdo->query('SELECT * FROM players ORDER BY id DESC');
    return $stmt->fetchAll();
}

function insertPlayer(PDO $pdo, array $data): void {
    $stmt = $pdo->prepare('INSERT INTO players (nama, umur, ovr, posisi, keahlian, gaya_main) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $data['nama'],
        $data['umur'],
        $data['ovr'],
        $data['posisi'],
        $data['keahlian'],
        $data['gaya_main']
    ]);
}

function validatePlayerData(array $input): array {
    $errors = [];

    if (trim($input['nama'] ?? '') === '') {
        $errors[] = 'Nama tidak boleh kosong.';
    }

    $umur = intval($input['umur'] ?? 0);
    if ($umur < 18 || $umur > 40) {
        $errors[] = 'Umur harus antara 10 dan 40.';
    }

    $ovr = intval($input['ovr'] ?? -1);
    if ($ovr < 0 || $ovr > 240) {
        $errors[] = 'OVR harus antara 0 dan 240.';
    }

    $posisi_options = ['GK', 'DC', 'DL', 'DR', 'DMC', 'MC', 'MR', 'ML', 'AML', 'AMR', 'AMC', 'ST'];
    if (!in_array($input['posisi'] ?? '', $posisi_options)) {
        $errors[] = 'Posisi tidak valid.';
    }

    $keahlian = intval($input['keahlian'] ?? -1);
    if ($keahlian < 0 || $keahlian > 2) {
        $errors[] = 'Keahlian harus antara 0 dan 2.';
    }

    if (!in_array($input['gaya_main'] ?? '', ['Ada', 'Tidak Ada'])) {
        $errors[] = 'Gaya Main tidak valid.';
    }

    return $errors;
}
