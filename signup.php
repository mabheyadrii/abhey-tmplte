<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        $stmt = $conn->prepare("INSERT INTO tbl_pengguna (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            echo "Pendaftaran berhasil! Silakan <a href='login.php'>login</a>.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
            <ul>
                <h1>Silahkan Untuk Mendaftarkan Diri</h1>
                <li><a href="login.php">Klik di sini, setelah mendaftar !</a></li>
                <h3>atau lihat atas kiri samping</h3>
            </ul>
        </nav>
    </header>
    <form method="post" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
