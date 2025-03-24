<?php
// menu/delete.php

include '../includes/db.php';

// Ambil ID dari parameter URL
$id_menu = $_GET['id'] ?? null;

if (!$id_menu) {
    die("ID tidak valid.");
}

// Hapus data
$sql = "DELETE FROM menu WHERE id_menu=$id_menu";
if ($conn->query($sql)) {
    header("Location: menu.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>