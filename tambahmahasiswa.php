<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $foto = '';

    if ($_FILES['foto']['name']) {
        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
    }

    $sql = "INSERT INTO tbl_mahasiswa (nim, nama, alamat, email, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nim, $nama, $alamat, $email, $foto);

    if ($stmt->execute()) {
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
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Tambah Mahasiswa</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" required>

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto">

            <label for="organisasi">Organisasi yang Diikuti:</label>
            <div id="organisasi-container">
                <input type="text" name="organisasi[]" required>
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

