<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Penduduk - Sistem Informasi Desa</title>
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
            <li class="active"><a href="<?= base_url('sekretaris/penduduk') ?>"><i class="fas fa-users"></i> <span class="menu-text">Data Penduduk</span></a></li>
            <li><a href="<?= base_url('sekretaris/kelahiran') ?>"><i class="fas fa-baby"></i> <span class="menu-text">Kelahiran</span></a></li>
            <li><a href="<?= base_url('sekretaris/kematian') ?>"><i class="fas fa-cross"></i> <span class="menu-text">Kematian</span></a></li>
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
            <h5 class="mb-0">Edit Data Penduduk</h5>
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
                  <?php endif ?>

                  <form action="<?= base_url('sekretaris/penduduk/update/' . $penduduk['id']) ?>" method="post">
                     <?= csrf_field() ?>

                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>NIK</label>
                              <input type="text" name="nik" class="form-control" value="<?= $penduduk['nik'] ?>" required>
                           </div>

                           <div class="form-group">
                              <label>Nama Lengkap</label>
                              <input type="text" name="nama_lengkap" class="form-control" value="<?= $penduduk['nama_lengkap'] ?>" required>
                           </div>

                           <div class="form-group">
                              <label>Tempat Lahir</label>
                              <input type="text" name="tempat_lahir" class="form-control" value="<?= $penduduk['tempat_lahir'] ?>" required>
                           </div>

                           <div class="form-group">
                              <label>Tanggal Lahir</label>
                              <input type="date" name="tanggal_lahir" class="form-control" value="<?= $penduduk['tanggal_lahir'] ?>" required>
                           </div>

                           <div class="form-group">
                              <label>Jenis Kelamin</label>
                              <select name="jenis_kelamin" class="form-control" required>
                                 <option value="L" <?= $penduduk['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                 <option value="P" <?= $penduduk['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Agama</label>
                              <input type="text" name="agama" class="form-control" value="<?= $penduduk['agama'] ?>" required>
                           </div>

                           <div class="form-group">
                              <label>Status Perkawinan</label>
                              <select name="status_perkawinan" class="form-control" required>
                                 <option value="belum_kawin" <?= $penduduk['status_perkawinan'] == 'belum_kawin' ? 'selected' : '' ?>>Belum Kawin</option>
                                 <option value="kawin" <?= $penduduk['status_perkawinan'] == 'kawin' ? 'selected' : '' ?>>Kawin</option>
                                 <option value="cerai_hidup" <?= $penduduk['status_perkawinan'] == 'cerai_hidup' ? 'selected' : '' ?>>Cerai Hidup</option>
                                 <option value="cerai_mati" <?= $penduduk['status_perkawinan'] == 'cerai_mati' ? 'selected' : '' ?>>Cerai Mati</option>
                              </select>
                           </div>

                           <div class="form-group">
                              <label>Pekerjaan</label>
                              <input type="text" name="pekerjaan" class="form-control" value="<?= $penduduk['pekerjaan'] ?>" required>
                           </div>

                           <div class="form-group">
                              <label>Penghasilan</label>
                              <input type="number" name="penghasilan" class="form-control" value="<?= $penduduk['penghasilan'] ?>">
                           </div>

                           <div class="form-group">
                              <label>Status Hidup</label>
                              <select name="status_hidup" class="form-control" required>
                                 <option value="1" <?= $penduduk['status_hidup'] == 1 ? 'selected' : '' ?>>Hidup</option>
                                 <option value="0" <?= $penduduk['status_hidup'] == 0 ? 'selected' : '' ?>>Meninggal</option>
                              </select>
                           </div>
                        </div>
                     </div>

                     <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" rows="1" value="<?= $penduduk['alamat'] ?>" required> </input>


                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>RT</label>
                                 <input type="text" name="rt" class="form-control" value="<?= $penduduk['rt'] ?>" required>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>RW</label>
                                 <input type="text" name="rw" class="form-control" value="<?= $penduduk['rw'] ?>" required>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Dusun</label>
                                 <input type="text" name="dusun" class="form-control" value="<?= $penduduk['dusun'] ?>" required>
                              </div>
                           </div>
                        </div>
                     </div>

                     <button type="submit" class="btn btn-primary mt-3">Update</button>
                     <a href="<?= base_url('sekretaris/penduduk') ?>" class="btn btn-secondary mt-3">Kembali</a>
                  </form>
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