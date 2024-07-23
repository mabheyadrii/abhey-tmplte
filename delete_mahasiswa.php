<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$nim = $_GET['nim'];

$sql_delete_organisasi = "DELETE FROM tbl_organisasi WHERE nim = '$nim'";
$conn->query($sql_delete_organisasi);

$sql_delete_mahasiswa = "DELETE FROM tbl_mahasiswa WHERE nim = '$nim'";
if ($conn->query($sql_delete_mahasiswa) === TRUE) {
    echo "Data mahasiswa berhasil dihapus";
} else {
    echo "Error: " . $conn->error;
}

header("Location: index.php");
exit;
?>
