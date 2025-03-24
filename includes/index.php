<?php
// index.php

include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white min-h-screen">
        <div class="p-4">
            <h1 class="text-2xl font-bold">Kasir App</h1>
        </div>
        <ul class="mt-4">
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="menu/menu.php" class="block">Menu</a>
            </li>
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="pelanggan/pelanggan.php" class="block">Pelanggan</a>
            </li>
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="karyawan/karyawan.php" class="block">Karyawan</a>
            </li>
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="meja/meja.php" class="block">Meja</a>
            </li>
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="transaksi/transaksi.php" class="block">Transaksi</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold">Selamat Datang di Kasir App</h1>
        <p class="mt-4">Pilih menu di sidebar untuk mengelola data.</p>
    </div>
</body>
</html>