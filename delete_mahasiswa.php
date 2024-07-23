<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    $sql = "DELETE FROM tbl_mahasiswa WHERE nim='$nim'";
    if ($conn->query($sql) === TRUE) {
        echo "<h3>Data mahasiswa berhasil dihapus</h3>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "NIM tidak ditemukan di URL.";
}
?>
