<?php
// transaksi/edit.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_transaksi = $_GET['id'] ?? null;

if (!$id_transaksi) {
    die("ID tidak valid.");
}

// Ambil data transaksi berdasarkan ID
$sql = "SELECT * FROM transaksi WHERE id_transaksi=$id_transaksi";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$transaksi = $result->fetch_assoc();

// Fetch Pelanggan
$sql_pelanggan = "SELECT id_pelanggan, nama_pelanggan FROM pelanggan";
$pelanggan_result = $conn->query($sql_pelanggan);

// Fetch Karyawan
$sql_karyawan = "SELECT id_karyawan, nama_karyawan FROM karyawan";
$karyawan_result = $conn->query($sql_karyawan);

// Fetch Meja
$sql_meja = "SELECT id_meja, nomor_meja FROM meja";
$meja_result = $conn->query($sql_meja);

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    // Validasi input
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_karyawan = $_POST['id_karyawan'];
    $id_meja = $_POST['id_meja'];
    $tanggal = $_POST['tanggal'];
    $total_harga = $_POST['total_harga'];
    $diskon = $_POST['diskon'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = $_POST['status'];
    $total_bayar = $_POST['total_bayar']; // Input Total Bayar

    // Validasi total harga, diskon, dan total bayar
    if ($total_harga < 0 || $diskon < 0 || $total_bayar < 0) {
        die("Total harga, diskon, atau total bayar tidak boleh negatif.");
    }

    // Query untuk update data transaksi
    $sql = "UPDATE transaksi SET 
                id_pelanggan='$id_pelanggan', 
                id_karyawan='$id_karyawan', 
                id_meja='$id_meja', 
                tanggal='$tanggal', 
                total_harga='$total_harga', 
                diskon='$diskon', 
                metode_pembayaran='$metode_pembayaran', 
                status='$status',
                total_bayar='$total_bayar' 
            WHERE id_transaksi=$id_transaksi";

    if ($conn->query($sql)) {
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
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white min-h-screen fixed top-0 left-0">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-green-500">Kasir App</h1>
        </div>
        <ul class="mt-8">
            <!-- Menu -->
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                <i class="fas fa-utensils text-lg"></i>
                <a href="../menu/menu.php" class="block flex-grow">Menu</a>
            </li>

            <!-- Pelanggan -->
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                <i class="fas fa-users text-lg"></i>
                <a href="../pelanggan/pelanggan.php" class="block flex-grow">Pelanggan</a>
            </li>

            <!-- Karyawan -->
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                <i class="fas fa-user-tie text-lg"></i>
                <a href="../karyawan/karyawan.php" class="block flex-grow">Karyawan</a>
            </li>

            <!-- Meja -->
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                <i class="fas fa-chair text-lg"></i>
                <a href="../meja/meja.php" class="block flex-grow">Meja</a>
            </li>

            <!-- Transaksi -->
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 bg-gray-700">
                <i class="fas fa-cash-register text-lg"></i>
                <a href="../transaksi/transaksi.php" class="block flex-grow">Transaksi</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Edit Transaksi</h1>

        <!-- Form Edit -->
        <form method="POST" class="bg-white shadow-md rounded-lg p-8 max-w-3xl space-y-8">
            <!-- Section: Data Pelanggan -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Data Pelanggan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pelanggan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pelanggan</label>
                        <select name="id_pelanggan" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <?php while ($row = $pelanggan_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_pelanggan']; ?>" <?php echo $row['id_pelanggan'] == $transaksi['id_pelanggan'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nama_pelanggan']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Karyawan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan</label>
                        <select name="id_karyawan" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <?php while ($row = $karyawan_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_karyawan']; ?>" <?php echo $row['id_karyawan'] == $transaksi['id_karyawan'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nama_karyawan']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section: Detail Transaksi -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Transaksi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Meja -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meja</label>
                        <select name="id_meja" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <?php while ($row = $meja_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_meja']; ?>" <?php echo $row['id_meja'] == $transaksi['id_meja'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nomor_meja']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="datetime-local" name="tanggal" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($transaksi['tanggal']))); ?>" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Total Harga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                        <input type="number" name="total_harga" value="<?php echo htmlspecialchars($transaksi['total_harga']); ?>" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Diskon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Diskon</label>
                        <input type="number" name="diskon" value="<?php echo htmlspecialchars($transaksi['diskon']); ?>" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" min="0">
                    </div>
                </div>
            </div>

            <!-- Section: Pembayaran -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Cash" <?php echo $transaksi['metode_pembayaran'] == 'Cash' ? 'selected' : ''; ?>>Cash</option>
                            <option value="Debit" <?php echo $transaksi['metode_pembayaran'] == 'Debit' ? 'selected' : ''; ?>>Debit</option>
                            <option value="QRIS" <?php echo $transaksi['metode_pembayaran'] == 'QRIS' ? 'selected' : ''; ?>>QRIS</option>
                            <option value="Transfer" <?php echo $transaksi['metode_pembayaran'] == 'Transfer' ? 'selected' : ''; ?>>Transfer</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Lunas" <?php echo $transaksi['status'] == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
                            <option value="Pending" <?php echo $transaksi['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>

                    <!-- Total Bayar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Bayar</label>
                        <input type="number" name="total_bayar" value="<?php echo htmlspecialchars($transaksi['total_bayar']); ?>" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div class="flex justify-between">
                <button type="submit" name="update" class="bg-green-500 text-white px-6 py-3 rounded-lg flex items-center gap-2 hover:bg-green-600 transition duration-300">
                    <i class="fas fa-save"></i>
                    <span>Simpan</span>
                </button>
                <a href="transaksi.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg flex items-center gap-2 hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </form>
    </div>
</body>
</html>