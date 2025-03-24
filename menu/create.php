<?php
// menu/create.php

include '../includes/db.php';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    $sql = "INSERT INTO menu (nama_menu, harga, kategori, deskripsi, status) 
            VALUES ('$nama_menu', '$harga', '$kategori', '$deskripsi', '$status')";
    if ($conn->query($sql)) {
        header("Location: menu.php");
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
    <title>Tambah Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex bg-gray-100 font-sans">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white min-h-screen fixed top-0 left-0">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-green-500">Kasir App</h1>
        </div>
        <ul class="mt-8 space-y-2">
            <!-- Menu -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 bg-gray-700 rounded-md">
                <i class="fas fa-utensils text-lg"></i>
                <a href="../menu/menu.php" class="block flex-grow">Menu</a>
            </li>

            <!-- Pelanggan -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 rounded-md">
                <i class="fas fa-users text-lg"></i>
                <a href="../pelanggan/pelanggan.php" class="block flex-grow">Pelanggan</a>
            </li>

            <!-- Karyawan -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 rounded-md">
                <i class="fas fa-user-tie text-lg"></i>
                <a href="../karyawan/karyawan.php" class="block flex-grow">Karyawan</a>
            </li>

            <!-- Meja -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 rounded-md">
                <i class="fas fa-chair text-lg"></i>
                <a href="../meja/meja.php" class="block flex-grow">Meja</a>
            </li>

            <!-- Transaksi -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 rounded-md">
                <i class="fas fa-cash-register text-lg"></i>
                <a href="../transaksi/transaksi.php" class="block flex-grow">Transaksi</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Tambah Menu</h1>

        <!-- Form Create -->
        <form method="POST" class="bg-white shadow-md rounded-lg p-8 max-w-3xl space-y-6">
            <!-- Row 1: Nama Menu & Harga -->
            <div class="flex gap-6">
                <!-- Nama Menu -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                    <input type="text" name="nama_menu" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- Harga -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="harga" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
            </div>

            <!-- Row 2: Kategori & Status -->
            <div class="flex gap-6">
                <!-- Kategori -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg"></textarea>
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div class="flex justify-between gap-4">
                <!-- Tombol Kembali -->
                <a href="menu.php"
                    class="bg-gray-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-gray-600 transition duration-300 w-full text-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>

                <!-- Tombol Simpan -->
                <button type="submit" class="bg-green-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-green-600 transition duration-300 w-full text-lg">
                    <i class="fas fa-save"></i>
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
</body>

</html>