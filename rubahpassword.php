<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    echo "Username: " . $username . "<br>";
    echo "Current Password: " . $current_password . "<br>";
    echo "New Password: " . $new_password . "<br>";
    echo "Confirm New Password: " . $confirm_new_password . "<br>";

    $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo "User ditemukan di database<br>";
        echo "Hash Password di Database: " . $user['password'] . "<br>";

        if (password_verify($current_password, $user['password'])) {
            echo "Password saat ini benar<br>";
            if ($new_password == $confirm_new_password) {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE tbl_pengguna SET password = ? WHERE username = ?");
                $stmt->bind_param("ss", $hashed_new_password, $username);
                if ($stmt->execute()) {
                    echo "Password berhasil diubah.";
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "Password baru dan konfirmasi password tidak cocok.";
            }
        } else {
            echo "Password saat ini salah.";
        }
    } else {
        echo "User tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <h1>Rubah Password Akun Anda</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <form method="post" action="">
        Password Saat Ini: <input type="password" name="current_password" required><br>
        Password Baru: <input type="password" name="new_password" required><br>
        Konfirmasi Password Baru: <input type="password" name="confirm_new_password" required><br>
        <input type="submit" value="Ubah Password">
    </form>
</body>
</html>
