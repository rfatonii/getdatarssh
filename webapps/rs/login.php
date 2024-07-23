<?php
session_start();

$host = '192.168.1.60';
$dbname = 'servicereport';
$username = 'client';
$password = 'ariaviv1234';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Login berhasil
        $_SESSION['email'] = $email;
        header("Location: muhammad.php");
        exit();
    } else {
        // Login gagal
        echo "Login gagal. Periksa kembali email dan password Anda.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Login</title>
</head>
<body>
    <h2>Silakan masuk</h2>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="username" name="email" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
