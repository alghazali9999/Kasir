<?php
// karyawan/karyawan.php

include '../includes/db.php';

// Fetch Data
$sql = "SELECT * FROM karyawan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyawan Management</title>
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
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 rounded-md">
                <i class="fas fa-utensils text-lg"></i>
                <a href="../menu/menu.php" class="block flex-grow">Menu</a>
            </li>

            <!-- Pelanggan -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 rounded-md">
                <i class="fas fa-users text-lg"></i>
                <a href="../pelanggan/pelanggan.php" class="block flex-grow">Pelanggan</a>
            </li>

            <!-- Karyawan -->
            <li class="px-4 py-3 hover:bg-gray-700 cursor-pointer flex items-center gap-3 bg-gray-700 rounded-md">
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
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Karyawan Management</h1>
        <!-- Tautan ke halaman Create -->
        <a href="create.php"
            class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300 text-lg inline-block mb-8">
            <i class="fas fa-plus mr-2"></i>
            <span>Tambah Karyawan</span>
        </a>

        <!-- Table -->
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="w-full text-left border-collapse max-w-screen-xl mx-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-700">ID</th>
                        <th class="px-6 py-4 font-medium text-gray-700">Nama Karyawan</th>
                        <th class="px-6 py-4 font-medium text-gray-700">No Telepon</th>
                        <th class="px-6 py-4 font-medium text-gray-700">Email</th>
                        <th class="px-6 py-4 font-medium text-gray-700">Jabatan</th>
                        <th class="px-6 py-4 font-medium text-gray-700">Gaji</th>
                        <th class="px-6 py-4 font-medium text-gray-700">Tanggal Masuk</th>
                        <th class="px-6 py-4 font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['id_karyawan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['nama_karyawan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['jabatan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['gaji']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['tanggal_masuk']); ?></td>
                            <td class="px-6 py-4 flex items-center justify-center gap-3">
                                <!-- Edit -->
                                <a href="edit.php?id=<?php echo $row['id_karyawan']; ?>"
                                    class="text-blue-500 hover:text-blue-700" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>

                                <!-- Hapus -->
                                <a href="delete.php?id=<?php echo $row['id_karyawan']; ?>"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')"
                                    class="text-red-500 hover:text-red-700" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>