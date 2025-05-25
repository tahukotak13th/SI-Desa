<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Penduduk - Sistem Informasi Desa</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/css/sekreDashboard.css') ?>">
</head>

<body>
   <div class="sekretaris-wrapper">
      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
         <div class="sidebar-header">
            <h4>SID</h4>
            <p>Sekretaris Desa</p>
         </div>
         <ul class="sidebar-menu">
            <li><a href="<?= base_url('sekretaris/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>
            <li><a href="<?= base_url('sekretaris/penduduk') ?>"><i class="fas fa-users"></i> <span class="menu-text">Data Penduduk</span></a></li>
            <li class="active"><a href="<?= base_url('sekretaris/kelahiran') ?>"><i class="fas fa-baby"></i> <span class="menu-text">Kelahiran</span></a></li>
            <li><a href="<?= base_url('sekretaris/kematian') ?>"><i class="fas fa-skull"></i> <span class="menu-text">Kematian</span></a></li>
            <li><a href="<?= base_url('sekretaris/perkawinan') ?>"><i class="fas fa-heart"></i> <span class="menu-text">Perkawinan</span></a></li>
            <li><a href="<?= base_url('sekretaris/surat') ?>"><i class="fas fa-file-alt"></i> <span class="menu-text">Surat Keterangan</span></a></li>
            <li><a href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Logout</span></a></li>
         </ul>
      </div>

      <!-- Main Content -->
      <div class="main-content" id="main-content">
         <!-- Header -->
         <header class="header">
            <div class="toggle-sidebar" id="toggle-sidebar">
               <i class="fas fa-bars"></i>
            </div>
            <h5 class="mb-0">Tambah Data Kelahiran</h5>
            <div class="user-info">
               <span><?= session('nama_lengkap') ?></span>
               <i class="fas fa-user-circle"></i>
            </div>
         </header>

         <!-- Content -->
         <div class="container-fluid">
            <div class="card shadow mb-4">
               <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
               </div>
               <div class="card-body">
                  <?php if (session()->getFlashdata('errors')) : ?>
                     <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                           <p><?= $error ?></p>
                        <?php endforeach ?>
                     </div>
                  <?php endif; ?>

                  <form action="<?= base_url('sekretaris/kelahiran/simpan') ?>" method="post">
                     <?= csrf_field() ?>

                     <h5 class="mb-3">Data Bayi</h5>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>NIK Bayi</label>
                              <input type="text" name="nik" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Nama Lengkap Bayi</label>
                              <input type="text" name="nama_bayi" class="form-control" required>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Jenis Kelamin</label>
                              <select name="jenis_kelamin" class="form-control" required>
                                 <option value="L">Laki-laki</option>
                                 <option value="P">Perempuan</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Tanggal Lahir</label>
                              <input type="date" name="tanggal_lahir" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Tempat Lahir</label>
                              <input type="text" name="tempat_lahir" class="form-control" required>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Berat Badan (kg)</label>
                              <input type="number" step="0.01" name="berat_badan" class="form-control">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Panjang Badan (cm)</label>
                              <input type="number" step="0.1" name="panjang_badan" class="form-control">
                           </div>
                        </div>
                     </div>

                     <div class="form-group">
                        <label>Agama</label>
                        <select name="agama" class="form-control" required>
                           <option value="Islam">Islam</option>
                           <option value="Kristen">Kristen</option>
                           <option value="Katolik">Katolik</option>
                           <option value="Hindu">Hindu</option>
                           <option value="Buddha">Buddha</option>
                           <option value="Konghucu">Konghucu</option>
                        </select>
                     </div>

                     <h5 class="mb-3 mt-4">Data Orang Tua</h5>
                     <div class="form-group">
                        <label>Pasangan Orang Tua</label>
                        <select name="perkawinan_id" class="form-control" required>
                           <option value="">- Pilih Pasangan Orang Tua -</option>
                           <?php foreach ($pasangan as $p) : ?>
                              <option value="<?= $p['id'] ?>">
                                 <?= $p['nama_suami'] ?> (NIK: <?= $p['nik_suami'] ?>) &
                                 <?= $p['nama_istri'] ?> (NIK: <?= $p['nik_istri'] ?>)
                              </option>
                           <?php endforeach; ?>
                        </select>
                     </div>

                     <!-- <h5 class="mb-3 mt-4">Alamat</h5> -->



                     <!-- Tambahkan field hidden untuk data default -->
                     <input type="hidden" name="pendidikan_terakhir" value="Tidak Bersekolah">
                     <input type="hidden" name="pekerjaan" value="Belum Bekerja">
                     <input type="hidden" name="status_perkawinan" value="belum_kawin">
                     <input type="hidden" name="status_hidup" value="1">

                     <button type="submit" class="btn btn-primary">Simpan</button>
                     <a href="<?= base_url('sekretaris/kelahiran') ?>" class="btn btn-secondary">Kembali</a>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
      // Toggle Sidebar
      const toggleSidebar = () => {
         const sidebar = document.getElementById('sidebar');
         const content = document.getElementById('main-content');

         sidebar.classList.toggle('active');
         content.classList.toggle('active');

         // Save state to localStorage
         const isCollapsed = sidebar.classList.contains('active');
         localStorage.setItem('sidebarCollapsed', isCollapsed);
      };

      // Initialize state from localStorage
      document.addEventListener('DOMContentLoaded', () => {
         const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
         const sidebar = document.getElementById('sidebar');
         const content = document.getElementById('main-content');

         if (isCollapsed && window.innerWidth >= 992) {
            sidebar.classList.add('active');
            content.classList.add('active');
         }

         document.getElementById('toggle-sidebar').addEventListener('click', toggleSidebar);
      });
   </script>
</body>

</html>