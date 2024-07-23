<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM tbl_mahasiswa WHERE nama LIKE '%$search%' LIMIT $start, $limit";
$result = $conn->query($sql);

$sql_total = "SELECT COUNT(*) FROM tbl_mahasiswa WHERE nama LIKE '%$search%'";
$total_result = $conn->query($sql_total);
$total = $total_result->fetch_row()[0];

$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mahasiswa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Selamat Datang di website ku</h1>
    </header>
    <div class="container">
        <h2>Daftar Mahasiswa</h2>
        <form method="get" action="">
            <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Cari Nama">
            <input type="submit" value="Cari">
        </form>

        <a href="tambahmahasiswa.php" class="button">Tambah Mahasiswa</a>
        <a href="rubahpassword.php" class="button">Ubah Password</a>
        <a href="logout.php" class="button">Logout</a>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>Foto</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nim']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['alamat']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><img src="<?php echo $row['foto']; ?>" width="100"></td>
                            <td class="action-links">
                                <a href="detailmahasiswa.php?nim=<?php echo $row['nim']; ?>" class="detail">Detail</a> |
                                <a href="edit_mahasiswa.php?nim=<?php echo $row['nim']; ?>" class="edit">Edit</a> |
                                <a href="delete_mahasiswa.php?nim=<?php echo $row['nim']; ?>" class="delete">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data ditemukan.</p>
        <?php endif; ?>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
    <footer>
        <p>&copy;Sistem Informasi Pendaftaran Mahasiswa</p>
    </footer>
</body>
</html>

