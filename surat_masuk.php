<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php"; // koneksi ke db

// Tambah surat masuk
if (isset($_POST['simpan'])) {
    $nomor   = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $pengirim= mysqli_real_escape_string($conn, $_POST['pengirim']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);
    $isi     = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $queryInsert = "INSERT INTO surat_masuk (nomor_surat, tanggal, pengirim, perihal, isi) 
                    VALUES ('$nomor','$tanggal','$pengirim','$perihal','$isi')";
    mysqli_query($conn, $queryInsert);
    $_SESSION['pesan'] = "Surat masuk berhasil ditambahkan!";
    header("Location: surat_masuk.php");
    exit;
}

// Update surat masuk
if (isset($_POST['update'])) {
    $id      = intval($_POST['id']);
    $nomor   = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $pengirim= mysqli_real_escape_string($conn, $_POST['pengirim']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);
    $isi     = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $queryUpdate = "UPDATE surat_masuk SET 
                      nomor_surat='$nomor',
                      tanggal='$tanggal',
                      pengirim='$pengirim',
                      perihal='$perihal',
                      isi='$isi'
                    WHERE id=$id";
    mysqli_query($conn, $queryUpdate);
    $_SESSION['pesan'] = "Surat masuk berhasil diperbarui!";
    header("Location: surat_masuk.php");
    exit;
}

// Hapus surat masuk
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM surat_masuk WHERE id=$id");
    $_SESSION['pesan'] = "Surat masuk berhasil dihapus!";
    header("Location: surat_masuk.php");
    exit;
}

// Pencarian
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
}
if ($keyword != "") {
    $query = "SELECT * FROM surat_masuk 
              WHERE nomor_surat LIKE '%$keyword%' 
              OR perihal LIKE '%$keyword%' 
              OR pengirim LIKE '%$keyword%' 
              ORDER BY tanggal DESC";
} else {
    $query = "SELECT * FROM surat_masuk ORDER BY tanggal DESC";
}
$result = mysqli_query($conn, $query) or die("Query error: " . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Masuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background:#f8fafc; }
    .fade-in { animation: fadeIn 0.8s ease-in-out; }
    @keyframes fadeIn { from {opacity:0; transform: translateY(15px);} to {opacity:1; transform: translateY(0);} }
    .card-hover { transition: all .3s ease; }
    .card-hover:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
    .modal { display: none; }
    .modal.active { display: flex; animation: zoomIn 0.4s ease; }
    @keyframes zoomIn { from {transform:scale(0.8); opacity:0;} to {transform:scale(1); opacity:1;} }
    #sidebar { transform: translateX(-100%); transition: transform 0.3s ease-in-out; }
    #sidebar.active { transform: translateX(0); }
  </style>
</head>
<body class="bg-gray-50 fade-in">

 <!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-gradient-to-b from-red-700 via-red-800 to-red-900 shadow-xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col text-white">

  <!-- Header Logo -->
  <div class="p-6 border-b border-red-600 flex flex-col items-center">
    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white shadow-lg mb-3">
      <img src="img/logo.jpg" alt="Logo" class="w-full h-full object-cover">
    </div>
    <h2 class="text-lg font-extrabold tracking-wide">Arsip Surat</h2>
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
      <a href="dashboard.php" class="flex items-center p-3 rounded-xl relative group hover:bg-white/10 transition">
        <span class="absolute left-0 top-0 h-full w-1 bg-red-400 rounded-r-lg scale-y-0 group-hover:scale-y-100 transition origin-top"></span>
        <i class="fas fa-home mr-3 text-red-200 group-hover:text-red-400"></i>
        <span class="group-hover:text-red-100">Dashboard</span>
      </a>
    </li>
    <li>
      <a href="surat_masuk.php" class="flex items-center p-3 rounded-xl relative group bg-white/10 hover:bg-white/20 transition font-semibold text-red-100">
        <span class="absolute left-0 top-0 h-full w-1 bg-red-400 rounded-r-lg scale-y-100 transition origin-top"></span>
        <i class="fas fa-inbox mr-3 text-red-300"></i>
        <span>Surat Masuk</span>
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


  <!-- Overlay -->
  <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-40 z-40"></div>

  <!-- Navbar -->
  <div class="w-full flex items-center justify-between px-6 py-4 bg-red-600 shadow fade-in">
    <div class="flex items-center space-x-3">
      <button id="menu-btn" class="focus:outline-none">
        <i class="fas fa-bars text-xl text-white"></i>
      </button>
      <h1 class="text-lg md:text-xl font-semibold text-white">Surat Masuk</h1>
    </div>
    <p id="clock" class="text-sm text-white"></p>
  </div>

  <!-- Konten -->
  <div class="max-w-6xl mx-auto mt-6 px-6 fade-in">
    <?php if(isset($_SESSION['pesan'])): ?>
      <div class="mb-4 p-3 rounded-lg transition
                  <?= strpos($_SESSION['pesan'], 'berhasil') !== false ? 'bg-red-100 text-red-700' : 'bg-red-200 text-red-800' ?>">
        <?= $_SESSION['pesan']; ?>
      </div>
      <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-2xl font-bold">Surat Masuk</h2>
        <p class="text-gray-500">Kelola semua surat masuk</p>
      </div>
      <button id="openModal" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl shadow transition transform hover:scale-105">
        <i class="fas fa-plus mr-1"></i> Tambah Surat Masuk
      </button>
    </div>

    <!-- Form Pencarian -->
    <form method="get" class="flex mb-6 fade-in">
      <input type="text" name="cari" placeholder="Cari nomor surat, perihal, atau pengirim..." value="<?=htmlspecialchars($keyword)?>" class="flex-1 border rounded-l-xl px-4 py-2 focus:outline-none focus:ring focus:ring-red-200">
      <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 rounded-r-xl transition">Cari</button>
    </form>

    <!-- Daftar Surat -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php if(mysqli_num_rows($result) > 0){ while($row = mysqli_fetch_assoc($result)) { ?>
      <div class="bg-white border-2 border-red-600 rounded-xl p-6 shadow card-hover fade-in">
        <div class="flex items-center mb-3">
          <i class="fas fa-file-alt fa-2x text-red-600 mr-2"></i>
          <h5 class="text-lg font-bold"><?=$row['perihal']?></h5>
        </div>
        <p class="text-sm text-gray-500 mb-2">#<?=$row['nomor_surat']?></p>
        <p><i class="fas fa-calendar text-red-600 mr-2"></i><b>Tanggal:</b> <?=date("d F Y", strtotime($row['tanggal']))?></p>
        <p><i class="fas fa-building text-red-600 mr-2"></i><b>Dari:</b> <?=$row['pengirim']?></p>
        <p class="mt-2 text-gray-700"><?=$row['isi']?></p>
        <div class="flex justify-end mt-4 space-x-2">
          <button onclick="openEditModal(<?=htmlspecialchars(json_encode($row))?>)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition transform hover:scale-105">
            <i class="fas fa-pen-to-square"></i> Edit
          </button>
          <a href="?hapus=<?=$row['id']?>" onclick="return confirm('Yakin hapus surat ini?');" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition transform hover:scale-105">
            <i class="fas fa-trash"></i> Hapus
          </a>
        </div>
      </div>
      <?php }} else { ?>
        <div class="col-span-2 fade-in"><div class="bg-yellow-100 text-yellow-700 p-4 rounded-xl">Data surat tidak ditemukan.</div></div>
      <?php } ?>
    </div>
  </div>

  <!-- Modal Tambah Surat -->
  <div id="modal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 overflow-y-auto max-h-[90vh] fade-in">
      <h2 class="text-xl font-bold mb-4 text-red-600"><i class="fas fa-plus"></i> Tambah Surat Masuk</h2>
      <form method="post">
        <div class="mb-3"><label class="block text-sm font-medium">Nomor Surat</label><input type="text" name="nomor_surat" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Tanggal Surat</label><input type="date" name="tanggal" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Pengirim</label><input type="text" name="pengirim" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Perihal</label><input type="text" name="perihal" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Keterangan</label><textarea name="keterangan" class="w-full border rounded-lg px-3 py-2"></textarea></div>
        <div class="flex justify-end gap-3 mt-4">
          <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
          <button type="submit" name="simpan" class="px-4 py-2 bg-red-600 text-white rounded-lg transition transform hover:scale-105">Simpan Surat</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Surat -->
  <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 overflow-y-auto max-h-[90vh] fade-in">
      <h2 class="text-xl font-bold mb-4 text-red-600"><i class="fas fa-pen-to-square"></i> Edit Surat Masuk</h2>
      <form method="post">
        <input type="hidden" name="id" id="edit_id">
        <div class="mb-3"><label class="block text-sm font-medium">Nomor Surat</label><input type="text" name="nomor_surat" id="edit_nomor" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Tanggal Surat</label><input type="date" name="tanggal" id="edit_tanggal" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Pengirim</label><input type="text" name="pengirim" id="edit_pengirim" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Perihal</label><input type="text" name="perihal" id="edit_perihal" required class="w-full border rounded-lg px-3 py-2"></div>
        <div class="mb-3"><label class="block text-sm font-medium">Keterangan</label><textarea name="keterangan" id="edit_isi" class="w-full border rounded-lg px-3 py-2"></textarea></div>
        <div class="flex justify-end gap-3 mt-4">
          <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
          <button type="submit" name="update" class="px-4 py-2 bg-red-600 text-white rounded-lg transition transform hover:scale-105">Update Surat</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Script -->
  <script>
    const menuBtn = document.getElementById("menu-btn");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const modal = document.getElementById("modal");
    const openModal = document.getElementById("openModal");
    const closeModal = document.getElementById("closeModal");
    const editModal = document.getElementById("editModal");
    const closeEditModal = document.getElementById("closeEditModal");

    menuBtn.addEventListener("click", () => {
      sidebar.classList.toggle("active");
      overlay.classList.toggle("hidden");
    });
    overlay.addEventListener("click", () => {
      sidebar.classList.remove("active");
      overlay.classList.add("hidden");
      modal.classList.remove("active");
      editModal.classList.remove("active");
    });
openModal.addEventListener("click", () => {
  modal.classList.add("active");
});

    closeModal.addEventListener("click", () => { modal.classList.remove("active"); });
    closeEditModal.addEventListener("click", () => { editModal.classList.remove("active"); });

    function openEditModal(row) {
      document.getElementById('edit_id').value = row.id;

      document.getElementById('edit_nomor').value = row.nomor_surat;
      document.getElementById('edit_tanggal').value = row.tanggal;
      document.getElementById('edit_pengirim').value = row.pengirim;
      document.getElementById('edit_perihal').value = row.perihal;
      document.getElementById('edit_isi').value = row.isi;
      editModal.classList.add("active");
    }

    function updateClock() {
      const now = new Date();
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const date = now.toLocaleDateString('id-ID', options);
      const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      document.getElementById('clock').textContent = `${date} pukul ${time}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>
