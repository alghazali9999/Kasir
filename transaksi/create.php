<?php
// transaksi/create.php

include '../includes/db.php';

// Fetch Pelanggan
$sql_pelanggan = "SELECT id_pelanggan, nama_pelanggan FROM pelanggan";
$pelanggan_result = $conn->query($sql_pelanggan);

// Fetch Karyawan
$sql_karyawan = "SELECT id_karyawan, nama_karyawan FROM karyawan";
$karyawan_result = $conn->query($sql_karyawan);

// Fetch Meja
$sql_meja = "SELECT id_meja, nomor_meja FROM meja";
$meja_result = $conn->query($sql_meja);

// Fetch Menu
$sql_menu = "SELECT id_menu, nama_menu, harga FROM menu";
$menu_result = $conn->query($sql_menu);

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_karyawan = $_POST['id_karyawan'];
    $id_meja = $_POST['id_meja'];
    $tanggal = $_POST['tanggal'];
    $diskon = $_POST['diskon'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = $_POST['status'];

    // Ambil data menu yang dipilih
    $menu_ids = $_POST['menu_id'];
    $menu_jumlahs = $_POST['menu_jumlah'];

    // Hitung total harga
    $total_harga = 0;
    foreach ($menu_ids as $index => $id_menu) {
        $jumlah = $menu_jumlahs[$index];
        $sql_menu_price = "SELECT harga FROM menu WHERE id_menu = $id_menu";
        $result_menu_price = $conn->query($sql_menu_price);
        $menu = $result_menu_price->fetch_assoc();
        $subtotal = $menu['harga'] * $jumlah;
        $total_harga += $subtotal;
    }

    // Insert data transaksi
    $sql_insert_transaksi = "INSERT INTO transaksi (id_pelanggan, id_karyawan, id_meja, tanggal, total_harga, diskon, metode_pembayaran, status) 
                             VALUES ('$id_pelanggan', '$id_karyawan', '$id_meja', '$tanggal', '$total_harga', '$diskon', '$metode_pembayaran', '$status')";
    if ($conn->query($sql_insert_transaksi)) {
        $id_transaksi = $conn->insert_id; // Ambil ID transaksi terbaru

        // Insert detail transaksi
        foreach ($menu_ids as $index => $id_menu) {
            $jumlah = $menu_jumlahs[$index];
            $sql_menu_price = "SELECT harga FROM menu WHERE id_menu = $id_menu";
            $result_menu_price = $conn->query($sql_menu_price);
            $menu = $result_menu_price->fetch_assoc();
            $subtotal = $menu['harga'] * $jumlah;

            $sql_insert_detail = "INSERT INTO detail_transaksi (id_transaksi, id_menu, jumlah, subtotal) 
                                  VALUES ('$id_transaksi', '$id_menu', '$jumlah', '$subtotal')";
            $conn->query($sql_insert_detail);
        }

        header("Location: transaksi.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function tambahMenu() {
            const container = document.getElementById('menu-container');
            const newIndex = container.children.length;

            const newField = `
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Menu</label>
                        <select name="menu_id[]" class="w-full p-2 border rounded" onchange="updateSubtotal(this)" required>
                            <option value="" disabled selected>Pilih Menu</option>
                            <?php while ($row = $menu_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_menu']; ?>" data-harga="<?php echo $row['harga']; ?>">
                                <?php echo $row['nama_menu']; ?> (Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Jumlah</label>
                        <input type="number" name="menu_jumlah[]" class="w-full p-2 border rounded" oninput="updateSubtotal(this)" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Subtotal</label>
                        <input type="text" name="menu_subtotal[]" class="w-full p-2 border rounded" readonly>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newField);
        }

        function updateSubtotal(element) {
            const row = element.closest('.flex');
            const selectMenu = row.querySelector('select[name="menu_id[]"]');
            const inputJumlah = row.querySelector('input[name="menu_jumlah[]"]');
            const inputSubtotal = row.querySelector('input[name="menu_subtotal[]"]');

            const harga = selectMenu.options[selectMenu.selectedIndex]?.getAttribute('data-harga') || 0;
            const jumlah = inputJumlah.value || 0;
            const subtotal = harga * jumlah;

            inputSubtotal.value = formatRupiah(subtotal);

            // Update total harga
            updateTotalHarga();
        }

        function updateTotalHarga() {
            const subtotals = Array.from(document.querySelectorAll('input[name="menu_subtotal[]"]'))
                .map(input => parseFloat(input.value.replace(/\D/g, '')) || 0);
            const totalHarga = subtotals.reduce((sum, val) => sum + val, 0);

            document.getElementById('total-harga').value = totalHarga;
        }

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
        }
    </script>
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white min-h-screen">
    <div class="p-4">
        <h1 class="text-2xl font-bold">Kasir App</h1>
    </div>
    <ul class="mt-4">
        <!-- Menu -->
        <li class="p-4 hover:bg-gray-700 cursor-pointer flex items-center gap-2">
            <i class="fas fa-utensils text-lg"></i>
            <a href="../menu/menu.php" class="block flex-grow">Menu</a>
        </li>

        <!-- Pelanggan -->
        <li class="p-4 hover:bg-gray-700 cursor-pointer flex items-center gap-2">
            <i class="fas fa-users text-lg"></i>
            <a href="../pelanggan/pelanggan.php" class="block flex-grow">Pelanggan</a>
        </li>

        <!-- Karyawan -->
        <li class="p-4 hover:bg-gray-700 cursor-pointer flex items-center gap-2">
            <i class="fas fa-user-tie text-lg"></i>
            <a href="../karyawan/karyawan.php" class="block flex-grow">Karyawan</a>
        </li>

        <!-- Meja -->
        <li class="p-4 hover:bg-gray-700 cursor-pointer flex items-center gap-2">
            <i class="fas fa-chair text-lg"></i>
            <a href="../meja/meja.php" class="block flex-grow">Meja</a>
        </li>

        <!-- Transaksi -->
        <li class="p-4 hover:bg-gray-700 cursor-pointer flex items-center gap-2">
            <i class="fas fa-cash-register text-lg"></i>
            <a href="../transaksi/transaksi.php" class="block flex-grow">Transaksi</a>
        </li>
    </ul>
</div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold">Tambah Transaksi</h1>

        <!-- Form Create -->
        <form method="POST" class="mt-8">
            <div class="mb-4">
                <label class="block text-sm font-medium">Pelanggan</label>
                <select name="id_pelanggan" class="w-full p-2 border rounded" required>
                    <?php while ($row = $pelanggan_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_pelanggan']; ?>"><?php echo $row['nama_pelanggan']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Karyawan</label>
                <select name="id_karyawan" class="w-full p-2 border rounded" required>
                    <?php while ($row = $karyawan_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_karyawan']; ?>"><?php echo $row['nama_karyawan']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Meja</label>
                <select name="id_meja" class="w-full p-2 border rounded" required>
                    <?php while ($row = $meja_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_meja']; ?>"><?php echo $row['nomor_meja']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Tanggal</label>
                <input type="datetime-local" name="tanggal" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Diskon</label>
                <input type="number" name="diskon" class="w-full p-2 border rounded" value="0">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="w-full p-2 border rounded">
                    <option value="Cash">Cash</option>
                    <option value="Debit">Debit</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full p-2 border rounded">
                    <option value="Lunas">Lunas</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>

            <!-- Input Menu -->
            <h2 class="text-xl font-bold mb-4">Daftar Menu</h2>
            <div id="menu-container">
                
            </div>
            <button type="button" onclick="tambahMenu()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Menu</button>

            <div class="mb-4">
                <label class="block text-sm font-medium">Total Harga</label>
                <input type="number" id="total-harga" name="total_harga" class="w-full p-2 border rounded" readonly required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</body>
</html>