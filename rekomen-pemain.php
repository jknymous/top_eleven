<?php
// rekomen-pemain.php

// Fetch players from the database
$stmt = $pdo->query('SELECT * FROM players');
$players = $stmt->fetchAll();

$posisi_options = ['GK', 'DC', 'DR', 'DL', 'DMC', 'MC', 'MR', 'ML', 'AMC', 'AMR', 'AML', 'ST'];

function calculateRecommendation($player) {
    $score = 0;

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

    if ($score >= 6) {
        return 'Upgrade';
    } elseif ($score == 5) {
        return 'Your Decision';
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

// Find best player per position for purple highlight
$bestPlayers = [];
foreach ($recommendations as $rec) {
    $pos = $rec['player']['posisi'];
    if (!isset($bestPlayers[$pos]) || $rec['player']['ovr'] > $bestPlayers[$pos]['player']['ovr']) {
        $bestPlayers[$pos] = $rec;
    }
}
?>

<style>
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        border: 3px solid black;
        color: black;
        font-weight: bold;
        font-size: 0.875rem;
    }
    .badge-gk { background-color: skyblue; }
    .badge-dc, .badge-dr, .badge-dl { background-color: green; }
    .badge-dmc, .badge-mc, .badge-mr, .badge-ml, .badge-amc, .badge-amr, .badge-aml { background-color: orange; }
    .badge-st { background-color: red; }
    table:hover tbody tr:hover { background-color: rgba(59,130,246,0.1) !important; }
    th[data-column] {
        cursor: pointer;
    }
</style>

<h1 class="text-4xl font-extrabold text-blue-700 mb-6 select-none">Rekomendasi Pemain</h1>

<?php if (empty($recommendations)): ?>
    <p class="text-lg text-gray-600">Belum ada pemain untuk direkomendasikan.</p>
<?php else: ?>
    <div class="overflow-x-auto">
        <table id="recommendation-table" class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                <tr>
                    <th class="text-left py-3 px-4" data-column="posisi" data-order="asc">Posisi &#9650;</th>
                    <th class="text-left py-3 px-4" data-column="nama" data-order="asc">Nama &#9650;</th>
                    <th class="text-left py-3 px-4" data-column="umur" data-order="asc">Umur &#9650;</th>
                    <th class="text-left py-3 px-4" data-column="ovr" data-order="asc">OVR &#9650;</th>
                    <th class="text-left py-3 px-4" data-column="rekomendasi" data-order="asc">Rekomendasi &#9650;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recommendations as $rec):
                    $player = $rec['player'];
                    $isBest = isset($bestPlayers[$player['posisi']]) && $bestPlayers[$player['posisi']]['player']['id'] === $player['id'];
                    $posClass = '';
                    switch ($player['posisi']) {
                        case 'GK': $posClass = 'badge badge-gk'; break;
                        case 'DC': case 'DR': case 'DL': $posClass = 'badge badge-dc'; break;
                        case 'DMC': case 'MC': case 'MR': case 'ML': case 'AMC': case 'AMR': case 'AML': $posClass = 'badge badge-dmc'; break;
                        case 'ST': $posClass = 'badge badge-st'; break;
                        default: $posClass = 'badge';
                    }
                    $recommendationText = $rec['recommendation'];
                ?>
                <tr>
                    <td class="py-2 px-4"><span class="<?= $posClass ?>"><?= htmlspecialchars($player['posisi']) ?></span></td>
                    <td class="py-2 px-4 <?= $isBest ? 'text-purple-600 font-bold' : '' ?>"><?= htmlspecialchars($player['nama']) ?></td>
                    <td class="py-2 px-4"><?= $player['umur'] ?></td>
                    <td class="py-2 px-4"><?= $player['ovr'] ?></td>
                    <td class="py-2 px-4 font-semibold <?= 
                        $recommendationText === 'Upgrade' ? 'text-green-700' : 
                        ($recommendationText === 'Your Decision' ? 'text-yellow-600' : 'text-red-600') 
                    ?>">
                        <?= $recommendationText ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('recommendation-table');
    const tbody = table.querySelector('tbody');
    const headers = table.querySelectorAll('th[data-column]');

    const posisiOrder = ['GK', 'DC', 'DR', 'DL', 'DMC', 'MC', 'MR', 'ML', 'AMC', 'AMR', 'AML', 'ST'];

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const column = header.getAttribute('data-column');
            let order = header.getAttribute('data-order');
            order = order === 'asc' ? 'desc' : 'asc';
            header.setAttribute('data-order', order);

            // Update arrow
            header.innerHTML = header.innerText.replace(/[\u25B2\u25BC]/g, '') + (order === 'asc' ? ' &#9650;' : ' &#9660;');

            // Reset other headers
            headers.forEach(h => {
                if(h !== header) {
                    h.setAttribute('data-order', 'asc');
                    h.innerHTML = h.innerText.replace(/[\u25B2\u25BC]/g, '') + ' &#9650;';
                }
            });

            const headerCells = Array.from(headers);
            const columnIndex = headerCells.findIndex(th => th.getAttribute('data-column') === column);

            const rowsArray = Array.from(tbody.querySelectorAll('tr'));

            rowsArray.sort((a, b) => {
                let valA = a.cells[columnIndex].innerText.trim();
                let valB = b.cells[columnIndex].innerText.trim();

                if(column === 'umur' || column === 'ovr') {
                    valA = Number(valA);
                    valB = Number(valB);
                    return order === 'asc' ? valA - valB : valB - valA;
                }

                if(column === 'posisi') {
                    const indexA = posisiOrder.indexOf(valA);
                    const indexB = posisiOrder.indexOf(valB);
                    const posA = indexA === -1 ? posisiOrder.length : indexA;
                    const posB = indexB === -1 ? posisiOrder.length : indexB;
                    return order === 'asc' ? posA - posB : posB - posA;
                }

                if(column === 'rekomendasi') {
                    const orderMap = { 'Upgrade': 3, 'Your Decision': 2, 'Sell': 1 };
                    const rankA = orderMap[valA] || 0;
                    const rankB = orderMap[valB] || 0;
                    return order === 'asc' ? rankA - rankB : rankB - rankA;
                }

                // Default string comparison
                return order === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
            });

            rowsArray.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>
