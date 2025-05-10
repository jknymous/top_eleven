<?php
// pemain.php
// Expects $players array to be passed in before include
?>

<h1 class="text-4xl font-extrabold text-blue-700 mb-6 select-none">Daftar Pemain</h1>

<?php if (!$players): ?>
    <p class="text-lg text-gray-600">Belum ada pemain yang ditambahkan.</p>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                <tr>
                    <th class="text-left py-3 px-4">Nama</th>
                    <th class="text-left py-3 px-4">Umur</th>
                    <th class="text-left py-3 px-4">OVR</th>
                    <th class="text-left py-3 px-4">Posisi</th>
                    <th class="text-left py-3 px-4">Keahlian</th>
                    <th class="text-left py-3 px-4">Gaya Main</th>
                    <th class="text-left py-3 px-4">Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $player): ?>
                    <tr class="border-t border-gray-200 hover:bg-blue-50">
                        <td class="py-2 px-4"><?= htmlspecialchars($player['nama']) ?></td>
                        <td class="py-2 px-4"><?= $player['umur'] ?></td>
                        <td class="py-2 px-4"><?= $player['ovr'] ?></td>
                        <td class="py-2 px-4"><?= $player['posisi'] ?></td>
                        <td class="py-2 px-4"><?= $player['keahlian'] ?></td>
                        <td class="py-2 px-4"><?= $player['gaya_main'] ?></td>
                        <td class="py-2 px-4"><?= $player['kelas'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
