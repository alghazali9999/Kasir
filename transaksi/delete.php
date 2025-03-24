<?php
// transaksi/delete.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_transaksi = $_GET['id'] ?? null;

if (!$id_transaksi || !is_numeric($id_transaksi)) {
    die("ID tidak valid.");
}

// Hapus data detail transaksi terkait
$sql_detail = "DELETE FROM detail_transaksi WHERE id_transaksi=$id_transaksi";
$conn->query($sql_detail);

// Hapus data transaksi
$sql_transaksi = "DELETE FROM transaksi WHERE id_transaksi=$id_transaksi";
if ($conn->query($sql_transaksi)) {
    // Redirect kembali ke halaman transaksi setelah berhasil menghapus
    header("Location: transaksi.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>