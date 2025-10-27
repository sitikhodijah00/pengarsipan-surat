<?php
include "koneksi.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['nama_lengkap']; // simpan nama untuk ditampilkan
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Login gagal! Password salah.'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Login gagal! Email tidak ditemukan.'); window.location='index.php';</script>";
    }
}
?>
