<?php
// menu/edit.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_menu = $_GET['id'] ?? null;

if (!$id_menu) {
    die("ID tidak valid.");
}

// Ambil data menu berdasarkan ID
$sql = "SELECT * FROM menu WHERE id_menu=$id_menu";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$menu = $result->fetch_assoc();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($nama_menu) || empty($harga)) {
        die("Nama menu dan harga wajib diisi.");
    }

    $sql = "UPDATE menu SET nama_menu='$nama_menu', harga='$harga', kategori='$kategori', deskripsi='$deskripsi', status='$status' 
            WHERE id_menu=$id_menu";
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
    <title>Edit Menu</title>
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
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 bg-gray-700">
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
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3">
                <i class="fas fa-cash-register text-lg"></i>
                <a href="../transaksi/transaksi.php" class="block flex-grow">Transaksi</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Menu</h1>

        <!-- Form Edit -->
        <form method="POST" class="bg-white shadow-md rounded-lg p-8 max-w-4xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Menu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                    <input type="text" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="harga" value="<?php echo htmlspecialchars($menu['harga']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                        <option value="Makanan" <?php echo $menu['kategori'] == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                        <option value="Minuman" <?php echo $menu['kategori'] == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                        <option value="Dessert" <?php echo $menu['kategori'] == 'Dessert' ? 'selected' : ''; ?>>Dessert</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                        <option value="Tersedia" <?php echo $menu['status'] == 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="Habis" <?php echo $menu['status'] == 'Habis' ? 'selected' : ''; ?>>Habis</option>
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="6" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg"><?php echo htmlspecialchars($menu['deskripsi']); ?></textarea>
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div class="flex justify-between mt-6">
                <button type="submit" name="update" class="bg-green-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-green-600 transition duration-300 text-lg">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="menu.php" class="bg-gray-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-gray-600 transition duration-300 text-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </form>
    </div>
</body>
</html>