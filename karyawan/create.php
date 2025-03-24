<?php
// karyawan/create.php

include '../includes/db.php';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_karyawan = $_POST['nama_karyawan'];
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];
    $jabatan = $_POST['jabatan'];
    $gaji = $_POST['gaji'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $sql = "INSERT INTO karyawan (nama_karyawan, no_telepon, email, jabatan, gaji, tanggal_masuk) 
            VALUES ('$nama_karyawan', '$no_telepon', '$email', '$jabatan', '$gaji', '$tanggal_masuk')";
    if ($conn->query($sql)) {
        header("Location: karyawan.php");
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
    <title>Tambah Karyawan</title>
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
        <h1 class="text-3xl font-bold">Tambah Karyawan</h1>

        <!-- Form Create -->
        <form method="POST" class="mt-8">
            <div class="mb-4">
                <label class="block text-sm font-medium">Nama Karyawan</label>
                <input type="text" name="nama_karyawan" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">No Telepon</label>
                <input type="text" name="no_telepon" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Jabatan</label>
                <select name="jabatan" class="w-full p-2 border rounded">
                    <option value="Kasir">Kasir</option>
                    <option value="Chef">Chef</option>
                    <option value="Waiter">Waiter</option>
                    <option value="Manager">Manager</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Gaji</label>
                <input type="number" name="gaji" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</body>
</html>