<?php
// dashboard.php

// Assume $pdo is the PDO connection from config.php available here.

// Fetch total players count
$totalPlayers = 0;
$totalUpgrade = 0;
$totalHold = 0;
$totalSell = 0;
$avgAge = 0;
$avgOvr = 0;

// Fetch all players
$stmt = $pdo->query('SELECT * FROM players');
$players = $stmt->fetchAll();

if ($players) {
    $totalPlayers = count($players);

    // Function to calculate recommendation score to categorize players
    function calculateRecommendation($player) {
        $score = 0;

        // Age scoring
        if ($player['umur'] >= 18 && $player['umur'] <= 20) {
            $score += 6;
        } elseif ($player['umur'] >= 21 && $player['umur'] <= 24) {
            $score += 3;
        } elseif ($player['umur'] >= 25 && $player['umur'] <= 28) {
            $score += 2;
        } elseif ($player['umur'] >= 29 && $player['umur'] <= 34) {
            $score += 1;
        } elseif ($player['umur'] >= 35 && $player['umur'] <= 40) {
            $score += 0;
        }

        $score += $player['keahlian'];

        if ($player['gaya_main'] === 'Ada') {
            $score += 1;
        }

        if ($player['ovr'] >= 140 && $player['ovr'] <= 180) {
            $score += 2;
        } elseif ($player['ovr'] >= 100 && $player['ovr'] < 140) {
            $score += 1;
        }

        return $score;
    }

    $ageSum = 0;
    $ovrSum = 0;

    // Calculate counts for categories and averages
    foreach ($players as $player) {
        $score = calculateRecommendation($player);

        if ($score >= 6) {
            $totalUpgrade++;
        } elseif ($score == 5) {
            $totalHold++;
        } else {
            $totalSell++;
        }

        $ageSum += $player['umur'];
        $ovrSum += $player['ovr'];
    }

    $avgAge = $ageSum/$totalPlayers;
    $avgOvr = $ovrSum/$totalPlayers;
} else {
    $totalPlayers = 0;
}
?>

<h1 class="text-4xl font-extrabold text-blue-700 mb-8 select-none">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl">

    <!-- Total Players Card -->
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="text-5xl font-extrabold text-blue-600"><?= $totalPlayers ?></div>
        <div class="mt-2 text-lg font-semibold text-gray-700">Total Pemain</div>
    </div>

    <!-- Average Age Card -->
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="text-4xl font-extrabold text-blue-600"><?= $totalPlayers > 0 ? number_format($avgAge, 1) : '-' ?></div>
        <div class="mt-2 text-lg font-semibold text-gray-700">Rata-rata Umur</div>
    </div>

    <!-- Average OVR Card -->
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="text-4xl font-extrabold text-blue-600"><?= $totalPlayers > 0 ? number_format($avgOvr, 1) : '-' ?></div>
        <div class="mt-2 text-lg font-semibold text-gray-700">Rata-rata OVR</div>
    </div>

</div>

<div class="mt-10 max-w-5xl bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">Rekomendasi Pemain</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="border border-green-400 rounded p-4">
            <div class="text-3xl font-extrabold text-green-600"><?= $totalUpgrade ?></div>
            <div class="mt-1 font-semibold text-gray-700">Layak Upgrade</div>
        </div>
        <div class="border border-yellow-400 rounded p-4">
            <div class="text-3xl font-extrabold text-yellow-600"><?= $totalHold ?></div>
            <div class="mt-1 font-semibold text-gray-700">Hold</div>
        </div>
        <div class="border border-red-400 rounded p-4">
            <div class="text-3xl font-extrabold text-red-600"><?= $totalSell ?></div>
            <div class="mt-1 font-semibold text-gray-700">Layak Dijual</div>
        </div>
    </div>
</div>

<div class="mt-10 max-w-5xl bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">Distribusi Posisi Pemain</h2>
    <?php
    // Count players per position
    $posCount = [];
    $posisi_options = ['GK', 'DC', 'DL', 'DR', 'DMC', 'MC', 'MR', 'ML', 'AML', 'AMR', 'AMC', 'ST'];
    foreach ($posisi_options as $p) {
        $posCount[$p] = 0;
    }
    foreach ($players as $player) {
        if (isset($posCount[$player['posisi']])) {
            $posCount[$player['posisi']]++;
        }
    }
    ?>
    <ul class="grid grid-cols-3 md:grid-cols-6 gap-4">
        <?php foreach ($posCount as $pos => $count): ?>
            <li class="flex flex-col items-center border rounded p-3">
                <div class="text-xl font-bold text-blue-700"><?= $pos ?></div>
                <div class="text-lg font-semibold text-gray-700"><?= $count ?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

