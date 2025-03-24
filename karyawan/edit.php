<?php
// karyawan/edit.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_karyawan = $_GET['id'] ?? null;

if (!$id_karyawan) {
    die("ID tidak valid.");
}

// Ambil data karyawan berdasarkan ID
$sql = "SELECT * FROM karyawan WHERE id_karyawan=$id_karyawan";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$karyawan = $result->fetch_assoc();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $nama_karyawan = $_POST['nama_karyawan'];
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];
    $jabatan = $_POST['jabatan'];
    $gaji = $_POST['gaji'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // Validasi input
    if (empty($nama_karyawan) || empty($no_telepon) || empty($email) || empty($gaji) || empty($tanggal_masuk)) {
        die("Semua field wajib diisi.");
    }

    $sql = "UPDATE karyawan SET nama_karyawan='$nama_karyawan', no_telepon='$no_telepon', email='$email', jabatan='$jabatan', gaji='$gaji', tanggal_masuk='$tanggal_masuk' 
            WHERE id_karyawan=$id_karyawan";
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
    <title>Edit Karyawan</title>
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
            <li class="p-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 bg-gray-700">
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
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Karyawan</h1>

        <!-- Form Edit -->
        <form method="POST" class="bg-white shadow-md rounded-lg p-8 max-w-4xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Karyawan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Karyawan</label>
                    <input type="text" name="nama_karyawan" value="<?php echo htmlspecialchars($karyawan['nama_karyawan']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- No Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                    <input type="text" name="no_telepon" value="<?php echo htmlspecialchars($karyawan['no_telepon']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($karyawan['email']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <select name="jabatan" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                        <option value="Kasir" <?php echo $karyawan['jabatan'] == 'Kasir' ? 'selected' : ''; ?>>Kasir</option>
                        <option value="Chef" <?php echo $karyawan['jabatan'] == 'Chef' ? 'selected' : ''; ?>>Chef</option>
                        <option value="Waiter" <?php echo $karyawan['jabatan'] == 'Waiter' ? 'selected' : ''; ?>>Waiter</option>
                        <option value="Manager" <?php echo $karyawan['jabatan'] == 'Manager' ? 'selected' : ''; ?>>Manager</option>
                    </select>
                </div>

                <!-- Gaji -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gaji</label>
                    <input type="number" name="gaji" value="<?php echo htmlspecialchars($karyawan['gaji']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>

                <!-- Tanggal Masuk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="<?php echo htmlspecialchars($karyawan['tanggal_masuk']); ?>" class="w-full p-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div class="flex justify-between mt-6">
                <button type="submit" name="update" class="bg-green-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-green-600 transition duration-300 text-lg">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="karyawan.php" class="bg-gray-500 text-white px-8 py-4 rounded-lg flex items-center gap-2 hover:bg-gray-600 transition duration-300 text-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </form>
    </div>
</body>
</html>