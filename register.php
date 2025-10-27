<?php
session_start();
include "koneksi.php"; // koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek apakah email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['pesan'] = "Email sudah terdaftar, silakan login!";
        header("Location: register.php");
        exit;
    }

    // insert user baru
    $query = "INSERT INTO users (nama_lengkap, email, password) VALUES ('$nama_lengkap','$email','$password')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['pesan'] = "Registrasi berhasil, silakan login.";
        header("Location: index.php"); // redirect ke login
        exit;
    } else {
        $_SESSION['pesan'] = "Registrasi gagal: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Arsip Surat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css"> <!-- CSS dipisah -->
</head>
<body class="min-h-screen flex items-center justify-center">

  <!-- Card Registrasi -->
  <div class="bg-white w-96 p-8 rounded-2xl shadow-2xl fade-in">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <img src="img/logo_unhann.png" alt="Logo Instansi" class="w-20 h-20 rounded-full shadow-md">
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-800">Registrasi</h2>
    <p class="text-center text-gray-500 mb-6">Buat akun baru untuk mengakses sistem</p>

    <?php if(isset($_SESSION['pesan'])): ?>
      <div class="mb-4 p-3 text-center rounded-lg 
        <?= strpos($_SESSION['pesan'], 'berhasil') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
        <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required 
             class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400 outline-none">
      <input type="email" name="email" placeholder="Masukkan email" required 
             class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400 outline-none">
      <input type="password" name="password" placeholder="Masukkan password" required 
             class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400 outline-none">
      <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition transform hover:scale-105">
        Daftar
      </button>
    </form>

    <p class="text-center text-gray-600 text-sm mt-4">
      Sudah punya akun? 
      <a href="index.php" class="text-red-600 font-semibold hover:underline">Login</a>
    </p>
  </div>

</body>
</html>
