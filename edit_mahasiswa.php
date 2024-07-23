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
    $organisasi[] = $row['nama_organisasi'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $foto = $mahasiswa['foto'];

    if ($_FILES['foto']['name']) {
        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
    }

    $sql = "UPDATE tbl_mahasiswa SET nama = ?, alamat = ?, email = ?, foto = ? WHERE nim = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nama, $alamat, $email, $foto, $nim);

    if ($stmt->execute()) {
        $sql_delete_organisasi = "DELETE FROM tbl_organisasi WHERE nim = ?";
        $stmt_delete_organisasi = $conn->prepare($sql_delete_organisasi);
        $stmt_delete_organisasi->bind_param("s", $nim);
        $stmt_delete_organisasi->execute();

        $organisasi = $_POST['organisasi'];
        foreach ($organisasi as $org) {
            $sql_organisasi = "INSERT INTO tbl_organisasi (nim, nama_organisasi) VALUES (?, ?)";
            $stmt_organisasi = $conn->prepare($sql_organisasi);
            $stmt_organisasi->bind_param("ss", $nim, $org);
            $stmt_organisasi->execute();
        }

        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Edit Mahasiswa</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo $mahasiswa['nim']; ?>" readonly>

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo $mahasiswa['nama']; ?>" required>

            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" value="<?php echo $mahasiswa['alamat']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $mahasiswa['email']; ?>" required>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto">
            <?php if ($mahasiswa['foto']): ?>
                <img src="uploads/<?php echo $mahasiswa['foto']; ?>" width="100">
            <?php endif; ?>

            <label for="organisasi">Organisasi yang Diikuti:</label>
            <div id="organisasi-container">
                <?php foreach ($organisasi as $org): ?>
                    <input type="text" name="organisasi[]" value="<?php echo htmlspecialchars($org); ?>" required>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-organisasi">Tambah Organisasi</button>

            <input type="submit" value="Simpan">
        </form>
    </div>

    <script>
        document.getElementById('add-organisasi').addEventListener('click', function () {
            var container = document.getElementById('organisasi-container');
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'organisasi[]';
            input.required = true;
            container.appendChild(input);
        });
    </script>
</body>
</html>
