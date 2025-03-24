<?php
// pelanggan/edit.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_pelanggan = $_GET['id'] ?? null;

if (!$id_pelanggan) {
    die("ID tidak valid.");
}

// Ambil data pelanggan berdasarkan ID
$sql = "SELECT * FROM pelanggan WHERE id_pelanggan=$id_pelanggan";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$pelanggan = $result->fetch_assoc();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $poin_reward = $_POST['poin_reward'];

    $sql = "UPDATE pelanggan SET nama_pelanggan='$nama_pelanggan', no_telepon='$no_telepon', alamat='$alamat', email='$email', poin_reward='$poin_reward' 
            WHERE id_pelanggan=$id_pelanggan";
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
    <title>Edit Pelanggan</title>
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
                <a href="../menu/menu.php" class="block">Menu</a>
            </li>
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="pelanggan.php" class="block">Pelanggan</a>
            </li>
            <li class="p-4 hover:bg-gray-700 cursor-pointer">
                <a href="../karyawan.php" class="block">Karyawan</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold">Edit Pelanggan</h1>

        <!-- Form Edit -->
        <form method="POST" class="mt-8">
            <div class="mb-4">
                <label class="block text-sm font-medium">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" value="<?php echo $pelanggan['nama_pelanggan']; ?>" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">No Telepon</label>
                <input type="text" name="no_telepon" value="<?php echo $pelanggan['no_telepon']; ?>" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Alamat</label>
                <textarea name="alamat" class="w-full p-2 border rounded"><?php echo $pelanggan['alamat']; ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="<?php echo $pelanggan['email']; ?>" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Poin Reward</label>
                <input type="number" name="poin_reward" value="<?php echo $pelanggan['poin_reward']; ?>" class="w-full p-2 border rounded">
            </div>
            <button type="submit" name="update" class="bg-green-500 text-white px-4 py-2 rounded">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>