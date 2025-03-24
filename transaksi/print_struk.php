<?php
// print_struk.php

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

// Fetch Data Pelanggan
$sql_pelanggan = "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan={$transaksi['id_pelanggan']}";
$result_pelanggan = $conn->query($sql_pelanggan);
$pelanggan = $result_pelanggan->fetch_assoc();

// Fetch Data Karyawan
$sql_karyawan = "SELECT nama_karyawan FROM karyawan WHERE id_karyawan={$transaksi['id_karyawan']}";
$result_karyawan = $conn->query($sql_karyawan);
$karyawan = $result_karyawan->fetch_assoc();

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
    <title>Struk Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100" onload="window.print()">
    <div class="max-w-[300px] mx-auto bg-white p-6 rounded-lg shadow-md">
        <!-- Header -->
        <div class="text-center mb-4 border-b border-gray-200 pb-4">
            <h2 class="text-xl font-bold text-gray-700">Kasir App</h2>
            <p class="text-sm text-gray-500">Jl. Contoh No. 123<br>Telp: 0812-3456-7890</p>
        </div>

        <!-- Informasi Transaksi -->
        <div class="mb-4 space-y-2 border-b border-gray-200 pb-4">
            <p class="text-sm text-gray-700"><strong>ID Transaksi:</strong> <?php echo htmlspecialchars($transaksi['id_transaksi']); ?></p>
            <p class="text-sm text-gray-700"><strong>Tanggal:</strong> <?php echo htmlspecialchars($transaksi['tanggal']); ?></p>
            <p class="text-sm text-gray-700"><strong>Pelanggan:</strong> <?php echo htmlspecialchars($pelanggan['nama_pelanggan'] ?? '-'); ?></p>
            <p class="text-sm text-gray-700"><strong>Kasir:</strong> <?php echo htmlspecialchars($karyawan['nama_karyawan'] ?? '-'); ?></p>
        </div>

        <!-- Daftar Menu -->
        <div class="mb-4">
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left text-xs font-medium text-gray-600 py-1 px-2">Nama Menu</th>
                        <th class="text-left text-xs font-medium text-gray-600 py-1 px-2">Jumlah</th>
                        <th class="text-right text-xs font-medium text-gray-600 py-1 px-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $row): ?>
                    <tr class="border-b border-gray-200">
                        <td class="py-1 px-2 text-xs text-gray-700"><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                        <td class="py-1 px-2 text-xs text-gray-700"><?php echo htmlspecialchars($row['jumlah']); ?></td>
                        <td class="py-1 px-2 text-xs text-gray-700 text-right">Rp <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="space-y-2 border-t border-gray-200 pt-4">
            <div class="flex justify-between text-sm text-gray-700">
                <span>Total Harga:</span>
                <span>Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-700">
                <span>Diskon:</span>
                <span>Rp <?php echo number_format($diskon, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-700">
                <span>Harga Setelah Diskon:</span>
                <span>Rp <?php echo number_format($harga_setelah_diskon, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-700">
                <span>Total Bayar:</span>
                <span>Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-700">
                <span>Kembalian:</span>
                <span>Rp <?php echo number_format($kembalian, 0, ',', '.'); ?></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-gray-600 border-t border-gray-200 pt-4">
            <p>Terima kasih atas kunjungan Anda!</p>
        </div>
    </div>
</body>
</html>