<?php
// karyawan/delete.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_karyawan = $_GET['id'] ?? null;

if (!$id_karyawan) {
    die("ID tidak valid.");
}

// Hapus data
$sql = "DELETE FROM karyawan WHERE id_karyawan=$id_karyawan";
if ($conn->query($sql)) {
    header("Location: karyawan.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>