<?php
// meja/delete.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_meja = $_GET['id'] ?? null;

if (!$id_meja) {
    die("ID tidak valid.");
}

// Hapus data
$sql = "DELETE FROM meja WHERE id_meja=$id_meja";
if ($conn->query($sql)) {
    header("Location: meja.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>