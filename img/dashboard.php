<?php
// dashboard.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include "koneksi.php"; // koneksi ke database

// Hitung jumlah surat masuk
$qMasuk = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_masuk");
$dataMasuk = mysqli_fetch_assoc($qMasuk);
$totalMasuk = $dataMasuk['total'];

// Hitung jumlah surat keluar
$qKeluar = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_keluar");
$dataKeluar = mysqli_fetch_assoc($qKeluar);
$totalKeluar = $dataKeluar['total'];

// Hitung total arsip
$totalSemua = $totalMasuk + $totalKeluar;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Arsip Surat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    .fade-in { animation: fadeIn 1s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .card-hover:hover {
      transform: translateY(-6px) scale(1.02);
      box-shadow: 0 12px 20px rgba(0,0,0,0.15);
      transition: all .3s ease;
    }
    #sidebar {
      transform: translateX(-100%);
      transition: transform 0.3s ease-in-out;
    }
    #sidebar.active { transform: translateX(0); }

    /* Sidebar merah gradasi */
    .sidebar-bg {
      background: linear-gradient(180deg, #dc2626, #b91c1c, #7f1d1d);
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

 <!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-gradient-to-b from-red-700 via-red-800 to-red-900 shadow-2xl z-50 flex flex-col text-white transform -translate-x-full transition-transform duration-300 ease-in-out">

  <!-- Header Logo -->
  <div class="p-6 border-b border-red-600 flex flex-col items-center">
    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white shadow-lg mb-3">
      <img src="img/logo.jpg" alt="Logo" class="w-full h-full object-cover">
    </div>
    <h2 class="text-xl font-extrabold tracking-wide">Arsip Surat</h2>
    <p class="text-sm text-red-300">Unhan RI</p>
  </div>

  <!-- Profil Admin -->
  <div class="flex items-center mx-4 mt-6 p-4 rounded-2xl bg-white/10 backdrop-blur-md shadow-lg hover:bg-white/20 transition">
    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-white text-red-700 shadow-md">
      <i class="fas fa-user"></i>
    </div>
    <div class="ml-3 overflow-hidden">
      <h3 class="font-semibold truncate"><?= $_SESSION['username'] ?></h3>
    </div>
  </div>

  <!-- Menu -->
  <ul class="flex-1 mt-6 px-4 space-y-2">
    <li>
      <a href="dashboard.php" class="flex items-center p-3 rounded-xl relative group bg-white/10 font-semibold text-red-100 transition">
        <span class="absolute left-0 top-0 h-full w-1 bg-red-400 rounded-r-lg scale-y-100 transition origin-top"></span>
        <i class="fas fa-home mr-3 text-red-300"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a href="surat_masuk.php" class="flex items-center p-3 rounded-xl relative group hover:bg-white/10 transition">
        <span class="absolute left-0 top-0 h-full w-1 bg-red-400 rounded-r-lg scale-y-0 group-hover:scale-y-100 transition origin-top"></span>
        <i class="fas fa-inbox mr-3 text-red-200 group-hover:text-red-400"></i>
        <span class="group-hover:text-red-100">Surat Masuk</span>
      </a>
    </li>
    <li>
      <a href="surat_keluar.php" class="flex items-center p-3 rounded-xl relative group hover:bg-white/10 transition">
        <span class="absolute left-0 top-0 h-full w-1 bg-red-400 rounded-r-lg scale-y-0 group-hover:scale-y-100 transition origin-top"></span>
        <i class="fas fa-paper-plane mr-3 text-red-200 group-hover:text-red-400"></i>
        <span class="group-hover:text-red-100">Surat Keluar</span>
      </a>
    </li>
  </ul>

  <!-- Logout -->
  <div class="p-4 border-t border-red-700">
    <a href="logout.php" class="flex items-center p-3 rounded-xl text-red-300 hover:text-white hover:bg-red-600/30 transition font-semibold">
      <i class="fas fa-sign-out-alt mr-3"></i> Logout
    </a>
  </div>
</div>


<style>
  /* Animasi sidebar saat aktif */
  #sidebar.active {
    transform: translateX(0);
  }
</style>

  <!-- Overlay -->
  <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-40 z-40"></div>

  <!-- Navbar -->
  <div class="w-full flex items-center justify-between px-6 py-4 bg-red-600 shadow fade-in">
    <div class="flex items-center space-x-3">
      <button id="menu-btn" class="focus:outline-none">
        <i class="fas fa-bars text-xl text-white"></i>
      </button>
      <h1 class="text-lg md:text-xl font-semibold text-white">Dashboard</h1>
    </div>
    <p id="clock" class="text-sm text-white"></p>
  </div>

  <!-- Hero Section -->
  <div class="max-w-6xl mx-auto mt-6 px-6">
    <div class="bg-red-500 text-white rounded-xl shadow-lg p-8 flex items-center justify-between fade-in">
      <div>
        <h2 class="text-2xl md:text-3xl font-bold">Selamat Datang di Sistem Arsip Surat</h2>
        <p class="mt-2 text-lg">Kelola surat masuk dan keluar dengan mudah dan efisien</p>
      </div>
      <div class="hidden md:block">
        <div class="bg-red-600 p-4 rounded-2xl shadow-lg flex items-center justify-center">
          <i class="fas fa-envelope text-white text-5xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Cards -->
  <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 px-6">
    <!-- Surat Masuk -->
    <div class="bg-red-100 rounded-xl p-6 shadow card-hover fade-in">
      <div class="flex items-center space-x-4">
        <div class="bg-red-500 p-3 rounded-lg">
          <i class="fas fa-envelope text-white text-2xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-red-700">Surat Masuk</h3>
          <p class="text-2xl font-extrabold text-gray-800"><?= $totalMasuk ?></p>
          <p class="text-sm text-gray-500">Surat masuk</p>
        </div>
      </div>
    </div>

    <!-- Surat Keluar -->
    <div class="bg-yellow-100 rounded-xl p-6 shadow card-hover fade-in" style="animation-delay:0.2s;">
      <div class="flex items-center space-x-4">
        <div class="bg-yellow-500 p-3 rounded-lg">
          <i class="fas fa-paper-plane text-white text-2xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-yellow-700">Surat Keluar</h3>
          <p class="text-2xl font-extrabold text-gray-800"><?= $totalKeluar ?></p>
          <p class="text-sm text-gray-500">Surat keluar</p>
        </div>
      </div>
    </div>

    <!-- Total Arsip -->
    <div class="bg-purple-100 rounded-xl p-6 shadow card-hover fade-in" style="animation-delay:0.4s;">
      <div class="flex items-center space-x-4">
        <div class="bg-purple-500 p-3 rounded-lg">
          <i class="fas fa-folder text-white text-2xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-purple-700">Total Arsip</h3>
          <p class="text-2xl font-extrabold text-gray-800"><?= $totalSemua ?></p>
          <p class="text-sm text-gray-500">Total surat</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script>
    function updateClock() {
      const now = new Date();
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const date = now.toLocaleDateString('id-ID', options);
      const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      document.getElementById('clock').textContent = `${date} pukul ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    menuBtn.addEventListener('click', () => {
      sidebar.classList.toggle('active');
      overlay.classList.toggle('hidden');
    });
    overlay.addEventListener('click', () => {
      sidebar.classList.remove('active');
      overlay.classList.add('hidden');
    });
  </script>
</body>
</html>
