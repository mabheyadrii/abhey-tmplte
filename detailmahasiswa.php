<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$nim = $_GET['nim'];

$sql = "SELECT * FROM tbl_mahasiswa WHERE nim = '$nim'";
$result = $conn->query($sql);
$mahasiswa = $result->fetch_assoc();

$sql_organisasi = "SELECT * FROM tbl_organisasi WHERE nim = '$nim'";
$result_organisasi = $conn->query($sql_organisasi);
$organisasi = [];
while ($row = $result_organisasi->fetch_assoc()) {
    $organisasi[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Mahasiswa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Detail Mahasiswa</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="tambahmahasiswa.php">Tambah Mahasiswa</a></li>
            </ul>
        </nav>
    </header>
    <div class="container detail-container">
        <?php if ($mahasiswa): ?>
            <p><strong>NIM:</strong> <?= htmlspecialchars($mahasiswa['nim']) ?></p>
            <p><strong>Nama:</strong> <?= htmlspecialchars($mahasiswa['nama']) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($mahasiswa['alamat']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($mahasiswa['email']) ?></p>
            <?php if ($mahasiswa['foto']): ?>
                <p><strong>Foto:</strong><br><img src="uploads/<?= htmlspecialchars($mahasiswa['foto']) ?>" alt="Foto Mahasiswa"></p>
            <?php endif; ?>
            <h3>Organisasi yang Diikuti:</h3>
            <ul>
                <?php foreach ($organisasi as $row): ?>
                    <li><?= htmlspecialchars($row['nama_organisasi']) ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="index.php" class="button">Kembali</a>
        <?php else: ?>
            <p>Data mahasiswa tidak ditemukan.</p>
            <a href="index.php" class="button">Kembali</a>
        <?php endif; ?>
    </div>
</body>
</html>

