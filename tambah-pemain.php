<?php
// Variables $errors, $success, $oldData should be set before this include

function old($field) {
    global $oldData;
    return htmlspecialchars($oldData[$field] ?? '');
}

$posisi_options = ['GK', 'DC', 'DL', 'DR', 'DMC', 'MC', 'MR', 'ML', 'AML', 'AMR', 'AMC', 'ST'];
$keahlian_options = [0,1,2];
$gaya_options = ['Ada', 'Tidak Ada'];
$kelas_options = range(1,6);
?>

<h1 class="text-4xl font-extrabold text-blue-700 mb-8 select-none text-center">Tambah Pemain</h1>

<?php if ($success): ?>
    <div id="success-notification" class="fixed top-5 right-5 z-50 max-w-sm bg-green-600 text-white px-6 py-4 rounded shadow-lg opacity-0 transform translate-x-4 transition-opacity transition-transform duration-300">
        <?= $success ?>
    </div>
    <script>
        const notif = document.getElementById('success-notification');
        if (notif) {
            // Show notification with fade-in
            requestAnimationFrame(() => {
                notif.classList.remove('opacity-0', 'translate-x-4');
                notif.classList.add('opacity-100', 'translate-x-0');
            });
            // Hide notification after 4 seconds with fade-out
            setTimeout(() => {
                notif.classList.remove('opacity-100', 'translate-x-0');
                notif.classList.add('opacity-0', 'translate-x-4');
                // Remove from DOM after transition
                setTimeout(() => notif.remove(), 300);
            }, 4000);
        }
    </script>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="mb-6 p-4 max-w-3xl mx-auto text-red-800 bg-red-200 rounded">
        <ul class="list-disc list-inside space-y-1">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 bg-white p-8 rounded shadow">

    <div>
        <label for="nama" class="block mb-1 font-semibold text-blue-700">Nama</label>
        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= old('nama') ?>"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
    </div>

    <div>
        <label for="umur" class="block mb-1 font-semibold text-blue-700">Umur</label>
        <input
            type="number"
            id="umur"
            name="umur"
            min="18"
            max="40"
            value="<?= old('umur') ?>"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
    </div>

    <div>
        <label for="ovr" class="block mb-1 font-semibold text-blue-700">OVR</label>
        <input
            type="number"
            id="ovr"
            name="ovr"
            min="0"
            max="240"
            value="<?= old('ovr') ?>"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
    </div>

    <div>
        <label for="posisi" class="block mb-1 font-semibold text-blue-700">Posisi</label>
        <select
            id="posisi"
            name="posisi"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
            <?php
                foreach ($posisi_options as $pos) {
                    $sel = (old('posisi') === $pos) ? 'selected' : '';
                    echo "<option value=\"$pos\" $sel>$pos</option>";
                }
            ?>
        </select>
    </div>

    <div>
        <label for="keahlian" class="block mb-1 font-semibold text-blue-700">Keahlian (0-2)</label>
        <select
            id="keahlian"
            name="keahlian"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
            <?php
                foreach ($keahlian_options as $k) {
                    $sel = (old('keahlian') == $k) ? 'selected' : '';
                    echo "<option value=\"$k\" $sel>$k</option>";
                }
            ?>
        </select>
    </div>

    <div>
        <label for="gaya_main" class="block mb-1 font-semibold text-blue-700">Gaya Main</label>
        <select
            id="gaya_main"
            name="gaya_main"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
            <?php
                foreach ($gaya_options as $g) {
                    $sel = (old('gaya_main') === $g) ? 'selected' : '';
                    echo "<option value=\"$g\" $sel>$g</option>";
                }
            ?>
        </select>
    </div>

    <div>
        <label for="kelas" class="block mb-1 font-semibold text-blue-700">Kelas (1-6)</label>
        <select
            id="kelas"
            name="kelas"
            required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
            <?php
                foreach (range(1,6) as $c) {
                    $sel = (old('kelas') == $c) ? 'selected' : '';
                    echo "<option value=\"$c\" $sel>$c</option>";
                }
            ?>
        </select>
    </div>

    <div class="col-span-full text-center">
        <button type="submit" 
            class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold py-3 rounded shadow hover:from-blue-600 hover:to-blue-800 transition-colors duration-300">
            Tambah Pemain
        </button>
    </div>

</form>
