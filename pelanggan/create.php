<?php
// pelanggan/create.php

include '../includes/db.php';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $poin_reward = $_POST['poin_reward'];

    $sql = "INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat, email, poin_reward) 
            VALUES ('$nama_pelanggan', '$no_telepon', '$alamat', '$email', '$poin_reward')";
    if ($conn->query($sql)) {
        header("Location: pelanggan.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Tambah Pelanggan</title>
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
        <h1 class="text-3xl font-bold">Tambah Pelanggan</h1>

        <!-- Form Create -->
        <form method="POST" class="mt-8">
            <div class="mb-4">
                <label class="block text-sm font-medium">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">No Telepon</label>
                <input type="text" name="no_telepon" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Alamat</label>
                <textarea name="alamat" class="w-full p-2 border rounded"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Poin Reward</label>
                <input type="number" name="poin_reward" class="w-full p-2 border rounded" value="0">
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</body>
</html>