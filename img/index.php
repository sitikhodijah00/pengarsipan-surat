<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Arsip Surat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Background gradient animasi merah-putih */
    body {
      background: linear-gradient(-45deg, #ef4444, #dc2626, #ffffff, #f87171);
      background-size: 400% 400%;
      animation: gradientBG 12s ease infinite;
    }
    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Animasi fade-in */
    .fade-in {
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <!-- Card Login -->
  <div class="bg-white w-96 p-8 rounded-2xl shadow-2xl fade-in">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <img src="img/logo_unhann.png" alt="Logo Instansi" class="w-20 h-20 rounded-full shadow-md">
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-800">Sistem Arsip Surat</h2>
    <p class="text-center text-gray-500 mb-6">Masuk untuk mengakses sistem</p>

    <form action="login.php" method="post" class="space-y-4">
      <input type="email" name="email" placeholder="Masukkan email" required 
             class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400 outline-none">
      <input type="password" name="password" placeholder="Masukkan password" required 
             class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400 outline-none">
      <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition transform hover:scale-105">
        Masuk
      </button>
    </form>

    <p class="text-center text-gray-600 text-sm mt-4">
      Belum punya akun? 
      <a href="register.php" class="text-red-600 font-semibold hover:underline">Daftar</a>
    </p>
  </div>

</body>
</html>
