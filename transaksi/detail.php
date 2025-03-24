<?php
// transaksi/detail.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_transaksi = $_GET['id'] ?? null;

if (!$id_transaksi || !is_numeric($id_transaksi)) {
    die("ID tidak valid.");
}

// Fetch Data Transaksi Utama
$sql_transaksi = "SELECT * FROM transaksi WHERE id_transaksi=$id_transaksi";
$result_transaksi = $conn->query($sql_transaksi);

if ($result_transaksi->num_rows == 0) {
    die("Data transaksi tidak ditemukan.");
}

$transaksi = $result_transaksi->fetch_assoc();

// Fetch Data Detail Transaksi
$sql_detail = "SELECT dt.*, m.nama_menu 
               FROM detail_transaksi dt
               JOIN menu m ON dt.id_menu = m.id_menu
               WHERE dt.id_transaksi=$id_transaksi";
$result_detail = $conn->query($sql_detail);

// Hitung total harga otomatis dari detail transaksi
$total_harga = 0;
$details = [];
while ($row_detail = $result_detail->fetch_assoc()) {
    $total_harga += $row_detail['subtotal'];
    $details[] = $row_detail; // Simpan data detail untuk ditampilkan
}
$result_detail->data_seek(0); // Reset pointer untuk loop berikutnya

// Hitung harga setelah diskon dan kembalian
$diskon = $transaksi['diskon'];
$total_bayar = $transaksi['total_bayar'];

$harga_setelah_diskon = max(0, $total_harga - $diskon); // Pastikan tidak negatif
$kembalian = max(0, $total_bayar - $harga_setelah_diskon); // Pastikan tidak negatif
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
        <h1 class="text-3xl font-bold text-gray-800">Detail Transaksi</h1>

        <!-- Informasi Transaksi Utama -->
        <div class="bg-white shadow-md rounded p-6 mt-8 space-y-4">
            <h2 class="text-xl font-semibold text-green-600">Informasi Transaksi</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500"><strong>ID Transaksi:</strong></p>
                    <p class="text-base"><?php echo htmlspecialchars($transaksi['id_transaksi']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Tanggal:</strong></p>
                    <p class="text-base"><?php echo htmlspecialchars($transaksi['tanggal']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Total Harga:</strong></p>
                    <p class="text-base">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Diskon:</strong></p>
                    <p class="text-base">Rp <?php echo number_format($diskon, 0, ',', '.'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Harga Setelah Diskon:</strong></p>
                    <p class="text-base">Rp <?php echo number_format($harga_setelah_diskon, 0, ',', '.'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Total Bayar:</strong></p>
                    <p class="text-base">Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Kembalian:</strong></p>
                    <p class="text-base">Rp <?php echo number_format($kembalian, 0, ',', '.'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500"><strong>Status:</strong></p>
                    <p class="text-base"><?php echo htmlspecialchars($transaksi['status']); ?></p>
                </div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <h2 class="text-2xl font-bold text-gray-800 mt-10 mb-4">Daftar Menu</h2>
        <table class="w-full bg-white shadow-md rounded overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left text-sm font-medium text-gray-600">Nama Menu</th>
                    <th class="p-4 text-left text-sm font-medium text-gray-600">Jumlah</th>
                    <th class="p-4 text-right text-sm font-medium text-gray-600">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details)): ?>
                    <?php foreach ($details as $row): ?>
                        <tr class="border-b border-gray-200">
                            <td class="p-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                            <td class="p-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['jumlah']); ?></td>
                            <td class="p-4 text-sm text-gray-700 text-right">Rp
                                <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="p-4 text-center text-sm text-gray-500">Tidak ada detail transaksi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol Aksi -->
        <div class="flex gap-4 mt-8">
            <a href="print_struk.php?id=<?php echo $transaksi['id_transaksi']; ?>" target="_blank"
                class="bg-green-500 text-white px-4 py-2 rounded flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span>Cetak Struk</span>
            </a>
            <a href="transaksi.php" class="bg-gray-500 text-white px-4 py-2 rounded flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</body>

</html>