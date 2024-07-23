<?php
session_start();

// Fungsi untuk melakukan koneksi ke database
function connectDB()
{
    $servername = "192.168.1.60";
    $username = "client";
    $password = "ariaviv1234";
    $dbname = "servicereport";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    return $conn;
}

// Fungsi untuk melakukan login
function login($email, $password)
{
    $conn = connectDB();

    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM tbl_user WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: muhammad.php");
            exit();
        } else {
            // Password salah
            $_SESSION['login_error'] = "Email atau password salah.";
            header("Location: index.php");
            exit();
        }
    } else {
        // Pengguna tidak ditemukan
        $_SESSION['login_error'] = "Email atau password salah.";
        header("Location: index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    login($email, $password);
} else {
    header("Location: index.php");
    exit();
}
?>
