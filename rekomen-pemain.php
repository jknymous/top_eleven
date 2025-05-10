<?php
// rekomen-pemain.php

// Fetch players from the database
$stmt = $pdo->query('SELECT * FROM players');
$players = $stmt->fetchAll();

// Function to calculate score and recommendation
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

    // Skill scoring
    $score += $player['keahlian']; // Directly use skill level

    // Playing style scoring
    if ($player['gaya_main'] === 'Ada') {
        $score += 1; // Add points for having a playing style
    }

    // Class scoring
    switch ($player['kelas']) {
        case 6:
            $score += 5;
            break;
        case 5:
            $score += 4;
            break;
        case 4:
            $score += 3;
            break;
        case 3:
            $score += 2;
            break;
        case 2:
            $score += 1;
            break;
        case 1:
            $score += 0;
            break;
    }

    // OVR scoring
    if ($player['ovr'] >= 140 && $player['ovr'] <= 180) {
        $score += 2;
    } elseif ($player['ovr'] >= 100 && $player['ovr'] < 140) {
        $score += 1;
    }

    // Determine recommendation based on score
    if ($score >= 8) {
        return 'Upgrade';
    } elseif ($score >= 6) {
        return 'Hold';
    } else {
        return 'Sell';
    }
}

// Calculate recommendations for each player
$recommendations = [];
foreach ($players as $player) {
    $recommendations[] = [
        'player' => $player,
        'recommendation' => calculateRecommendation($player)
    ];
}
?>

<h1 class="text-4xl font-extrabold text-blue-700 mb-6 select-none">Rekomendasi Pemain</h1>

<?php if (empty($recommendations)): ?>
    <p class="text-lg text-gray-600">Belum ada pemain untuk direkomendasikan.</p>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                <tr>
                    <th class="text-left py-3 px-4">Nama</th>
                    <th class="text-left py-3 px-4">Umur</th>
                    <th class="text-left py-3 px-4">Keahlian</th>
                    <th class="text-left py-3 px-4">Gaya Main</th>
                    <th class="text-left py-3 px-4">Kelas</th>
                    <th class="text-left py-3 px-4">OVR</th>
                    <th class="text-left py-3 px-4">Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recommendations as $rec): ?>
                    <tr class="border-t border-gray-200 hover:bg-blue-50">
                        <td class="py-2 px-4"><?= htmlspecialchars($rec['player']['nama']) ?></td>
                        <td class="py-2 px-4"><?= $rec['player']['umur'] ?></td>
                        <td class="py-2 px-4"><?= $rec['player']['keahlian'] ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($rec['player']['gaya_main']) ?></td>
                        <td class="py-2 px-4"><?= $rec['player']['kelas'] ?></td>
                        <td class="py-2 px-4"><?= $rec['player']['ovr'] ?></td>
                        <td class="py-2 px-4 font-semibold">
                            <?php
                            switch ($rec['recommendation']) {
                                case 'Upgrade':
                                    echo '<span class="text-green-700">Upgrade</span>';
                                    break;
                                case 'Hold':
                                    echo '<span class="text-yellow-600">Hold</span>';
                                    break;
                                case 'Sell':
                                    echo '<span class="text-red-600">Sell</span>';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
