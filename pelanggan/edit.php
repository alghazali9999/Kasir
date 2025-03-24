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

    // Validasi input
    if (empty($nama_pelanggan) || empty($no_telepon)) {
        die("Nama pelanggan dan nomor telepon wajib diisi.");
    }

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
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 bg-gray-700">
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
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                <i class="fas fa-cash-register text-lg"></i>
                <a href="../transaksi/transaksi.php" class="block flex-grow">Transaksi</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Pelanggan</h1>

        <!-- Form Edit -->
        <form method="POST" class="bg-white shadow-md rounded-lg p-8 max-w-4xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Pelanggan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" value="<?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- No Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                    <input type="text" name="no_telepon" value="<?php echo htmlspecialchars($pelanggan['no_telepon']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="4" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg"><?php echo htmlspecialchars($pelanggan['alamat']); ?></textarea>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($pelanggan['email']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                </div>

                <!-- Poin Reward -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Poin Reward</label>
                    <input type="number" name="poin_reward" value="<?php echo htmlspecialchars($pelanggan['poin_reward']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                </div>
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div class="flex justify-between mt-6">
                <button type="submit" name="update" class="bg-green-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-green-600 transition duration-300 text-lg">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="pelanggan.php" class="bg-gray-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-gray-600 transition duration-300 text-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </form>
    </div>
</body>
</html>