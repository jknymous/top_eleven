<?php
// pemain.php
// Expects $players, $errors, $success passed before include

$posisi_options = ['GK', 'DC', 'DR', 'DL', 'DMC', 'MC', 'MR', 'ML', 'AMC', 'AMR', 'AML', 'ST'];
$keahlian_options = [0, 1, 2];
$gaya_options = ['Ada', 'Tidak Ada'];
?>

<style>
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px; /* Rounded corners */
        border: 3px solid black; /* Outline */
        color: black; /* Text color */
        font-weight: bold; /* Bold text */
        font-size: 0.875rem;
    }

    .badge-gk {
        background-color: skyblue; /* Light blue for GK */
    }

    .badge-dc,
    .badge-dr,
    .badge-dl {
        background-color: green; /* Green for DC, DR, DL */
    }

    .badge-dmc,
    .badge-mc,
    .badge-mr,
    .badge-ml,
    .badge-amc,
    .badge-amr,
    .badge-aml {
        background-color: orange; /* Yellow-orange for other mid fields */
    }

    .badge-st {
        background-color: red; /* Red for ST */
    }

    /* Keahlian and Gaya Main circle styles */
    .circle {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 4px;
        vertical-align: middle;
        border: 1.5px solid black;
    }
    .circle-gold {
        background-color: gold;
    }
    .circle-green {
        background-color: #22c55e; /* Tailwind green-500 */
    }
    .circle-red {
        background-color: #ef4444; /* Tailwind red-500 */
    }
    .circle-black {
        background-color: black; /* Tailwind red-500 */
    }
</style>

<h1 class="text-4xl font-extrabold text-blue-700 mb-6 select-none">Daftar Pemain</h1>

<?php if ($success): ?>
    <div id="success-notification" class="fixed top-5 right-5 z-50 max-w-sm bg-green-600 text-white px-6 py-4 rounded shadow-lg opacity-0 transform translate-x-4 transition-opacity transition-transform duration-300">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if (!$players): ?>
    <p class="text-lg text-gray-600">Belum ada pemain yang ditambahkan.</p>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-md">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                <tr>
                    <th class="text-left py-3 px-4 cursor-pointer" data-column="posisi" data-order="asc">Posisi &#9650;</th>
                    <th class="text-left py-3 px-4">Nama</th>
                    <th class="text-left py-3 px-4 cursor-pointer" data-column="umur" data-order="asc">Umur &#9650;</th>
                    <th class="text-left py-3 px-4">OVR</th>
                    <th class="text-left py-3 px-4">Keahlian</th>
                    <th class="text-left py-3 px-4">Gaya Main</th>
                    <th class="text-left py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $index => $player): ?>
                    <tr class="border-t border-gray-200 hover:bg-blue-50">
                        <td class="py-2 px-4">
                            <?php
                                $posisiClass = '';
                                switch ($player['posisi']) {
                                    case 'GK':
                                        $posisiClass = 'badge badge-gk';
                                        break;
                                    case 'DC':
                                        $posisiClass = 'badge badge-dc';
                                        break;
                                    case 'DR':
                                        $posisiClass = 'badge badge-dr';
                                        break;
                                    case 'DL':
                                        $posisiClass = 'badge badge-dl';
                                        break;
                                    case 'DMC':
                                        $posisiClass = 'badge badge-dmc';
                                        break;
                                    case 'MC':
                                        $posisiClass = 'badge badge-mc';
                                        break;
                                    case 'MR':
                                        $posisiClass = 'badge badge-mr';
                                        break;
                                    case 'ML':
                                        $posisiClass = 'badge badge-ml';
                                        break;
                                    case 'AMC':
                                        $posisiClass = 'badge badge-amc';
                                        break;
                                    case 'AMR':
                                        $posisiClass = 'badge badge-amr';
                                        break;
                                    case 'AML':
                                        $posisiClass = 'badge badge-aml';
                                        break;
                                    case 'ST':
                                        $posisiClass = 'badge badge-st';
                                        break;
                                    default:
                                        $posisiClass = 'badge';
                                }
                            ?>
                            <span class="<?= $posisiClass ?>"><?= htmlspecialchars($player['posisi']) ?></span>
                        </td>
                        <td class="py-2 px-4"><?= htmlspecialchars($player['nama']) ?></td>
                        <td class="py-2 px-4"><?= $player['umur'] ?></td>
                        <td class="py-2 px-4"><?= $player['ovr'] ?></td>
                        <td class="py-2 px-4">
                            <?php if ($player['keahlian'] > 0): ?>
                                <?php for ($i = 0; $i < $player['keahlian']; $i++): ?>
                                    <span class="circle circle-gold"></span>
                                <?php endfor; ?>
                            <?php else: ?>
                                <span class="circle circle-black"></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4">
                            <?php if ($player['gaya_main'] === 'Ada'): ?>
                                <span class="circle circle-green"></span>
                            <?php else: ?>
                                <span class="circle circle-red"></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 space-x-2">
                            <button
                                class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition-colors duration-200"
                                onclick='openEditModal(<?= htmlspecialchars(json_encode($player), ENT_QUOTES, "UTF-8") ?>)'>
                                Edit
                            </button>
                            <form method="POST" class="inline" onsubmit="return showDeleteConfirm(this)">
                                <input type="hidden" name="id" value="<?= $player['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="button"
                                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200"
                                    onclick="showDeleteConfirm(this.closest('form'))">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
        <h2 class="text-2xl font-semibold mb-4 text-blue-700">Edit Pemain</h2>
        <form id="edit-form" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="edit-id" />
            <input type="hidden" name="action" value="update" />

            <div>
                <label for="edit-nama" class="block mb-1 font-semibold text-blue-700">Nama</label>
                <input type="text" id="edit-nama" name="nama" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label for="edit-umur" class="block mb-1 font-semibold text-blue-700">Umur</label>
                <input type="number" id="edit-umur" name="umur" min="18" max="40" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label for="edit-ovr" class="block mb-1 font-semibold text-blue-700">OVR</label>
                <input type="number" id="edit-ovr" name="ovr" min="0" max="240" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label for="edit-posisi" class="block mb-1 font-semibold text-blue-700">Posisi</label>
                <select id="edit-posisi" name="posisi" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php foreach ($posisi_options as $pos): ?>
                        <option value="<?= $pos ?>"><?= $pos ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="edit-keahlian" class="block mb-1 font-semibold text-blue-700">Keahlian (0-2)</label>
                <select id="edit-keahlian" name="keahlian" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php foreach ($keahlian_options as $k): ?>
                        <option value="<?= $k ?>"><?= $k ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="edit-gaya_main" class="block mb-1 font-semibold text-blue-700">Gaya Main</label>
                <select id="edit-gaya_main" name="gaya_main" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php foreach ($gaya_options as $g): ?>
                        <option value="<?= $g ?>"><?= $g ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4 text-red-600">Konfirmasi Hapus</h3>
        <p class="mb-6">Apakah Anda yakin ingin menghapus pemain ini?</p>
        <div class="flex justify-end space-x-4">
            <button id="delete-cancel-btn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button id="delete-confirm-btn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const notif = document.getElementById('success-notification');
        if (notif) {
            requestAnimationFrame(() => {
                notif.classList.remove('opacity-0', 'translate-x-4');
                notif.classList.add('opacity-100', 'translate-x-0');
            });
            setTimeout(() => {
                notif.classList.remove('opacity-100', 'translate-x-0');
                notif.classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => notif.remove(), 300);
            }, 4000);
        }
    });

    function openEditModal(player) {
        const modal = document.getElementById('edit-modal');
        if (!modal) return;
        modal.classList.remove('hidden');

        document.getElementById('edit-id').value = player.id || '';
        document.getElementById('edit-nama').value = player.nama || '';
        document.getElementById('edit-umur').value = player.umur || '';
        document.getElementById('edit-ovr').value = player.ovr || '';
        document.getElementById('edit-posisi').value = player.posisi || '';
        document.getElementById('edit-keahlian').value = player.keahlian || '';
        document.getElementById('edit-gaya_main').value = player.gaya_main || '';
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        if (!modal) return;
        modal.classList.add('hidden');
    }

    window.addEventListener('keydown', (e) => {
        const modal = document.getElementById('edit-modal');
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeEditModal();
        }
    });

    document.getElementById('edit-modal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            closeEditModal();
        }
    });

    let deleteFormToSubmit = null;
    function showDeleteConfirm(form) {
        deleteFormToSubmit = form;
        document.getElementById('delete-confirm-modal').classList.remove('hidden');
        return false;  // Prevent immediate form submission
    }

    function hideDeleteConfirm() {
        document.getElementById('delete-confirm-modal').classList.add('hidden');
        deleteFormToSubmit = null;
    }

    document.getElementById('delete-cancel-btn').addEventListener('click', hideDeleteConfirm);
    document.getElementById('delete-confirm-btn').addEventListener('click', () => {
        if (deleteFormToSubmit) {
            deleteFormToSubmit.submit();
        }
        hideDeleteConfirm();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        const headers = table.querySelectorAll('th[data-column]');

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const column = header.getAttribute('data-column');
                let order = header.getAttribute('data-order');
                order = order === 'asc' ? 'desc' : 'asc';
                header.setAttribute('data-order', order);

                header.innerHTML = header.innerText.replace(/[\u25B2\u25BC]/g, '') + (order === 'asc' ? ' &#9650;' : ' &#9660;');

                headers.forEach(h => {
                    if (h !== header) {
                        h.setAttribute('data-order', 'asc');
                        h.innerHTML = h.innerText.replace(/[\u25B2\u25BC]/g, '') + ' &#9650;';
                    }
                });

                const headerCells = Array.from(table.querySelectorAll('th'));
                const columnIndex = headerCells.findIndex(th => th.getAttribute('data-column') === column);
                const rowsArray = Array.from(tbody.querySelectorAll('tr'));
                rowsArray.sort((a, b) => {
                    let valA = a.cells[columnIndex].innerText.trim();
                    let valB = b.cells[columnIndex].innerText.trim();

                    if (column === 'umur') {
                        valA = Number(valA);
                        valB = Number(valB);
                        return order === 'asc' ? valA - valB : valB - valA;
                    } else if (column === 'posisi') {
                        const customOrder = ['GK', 'DC', 'DR', 'DL', 'DMC', 'MC', 'MR', 'ML', 'AMC', 'AMR', 'AML', 'ST'];
                        const indexA = customOrder.indexOf(valA);
                        const indexB = customOrder.indexOf(valB);
                        const posA = indexA === -1 ? customOrder.length : indexA;
                        const posB = indexB === -1 ? customOrder.length : indexB;

                        return order === 'asc' ? posA - posB : posB - posA;
                    }
                    return 0;
                });
                rowsArray.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>