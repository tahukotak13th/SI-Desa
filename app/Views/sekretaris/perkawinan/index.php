<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Data Penduduk - Sistem Informasi Desa</title>
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
            <li><a href="<?= base_url('sekretaris/penduduk/') ?>"><i class="fas fa-users"></i> <span class="menu-text">Data Penduduk</span></a></li>
            <li><a href="<?= base_url('sekretaris/kelahiran') ?>"><i class="fas fa-baby"></i> <span class="menu-text">Kelahiran</span></a></li>
            <li><a href="<?= base_url('sekretaris/kematian') ?>"><i class="fas fa-skull"></i> <span class="menu-text">Kematian</span></a></li>
            <li class="active"><a href="<?= base_url('sekretaris/perkawinan') ?>"><i class="fas fa-heart"></i> <span class="menu-text">Perkawinan</span></a></li>
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
            <h5 class="mb-0">Data Penduduk</h5>
            <div class="user-info">
               <span><?= session('nama_lengkap') ?></span>
               <i class="fas fa-user-circle"></i>
            </div>
         </header>

         <!-- Content -->
         <div class="container-fluid p-4">
            <?php if (session()->getFlashdata('success')) : ?>
               <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
               <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card shadow mb-4">
               <div class="card-header py-3 d-flex justify-content-between align-items-center">
                  <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
                  <a href="<?= base_url('sekretaris/perkawinan/tambah') ?>" class="btn btn-primary btn-sm">
                     <i class="fas fa-plus"></i> Tambah Perkawinan
                  </a>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Suami</th>
                              <th>Istri</th>
                              <th>Tanggal Nikah</th>
                              <th>Tempat</th>
                              <th>Status</th>
                              <th>Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $no = 1; ?>
                           <?php foreach ($perkawinan as $p) : ?>
                              <tr>
                                 <td><?= $no++ ?></td>
                                 <td>
                                    <?= $p['nama_suami'] ?><br>
                                    <small class="text-muted">NIK: <?= $p['nik_suami'] ?></small>
                                 </td>
                                 <td>
                                    <?= $p['nama_istri'] ?><br>
                                    <small class="text-muted">NIK: <?= $p['nik_istri'] ?></small>
                                 </td>
                                 <td><?= date('d-m-Y', strtotime($p['tanggal_perkawinan'])) ?></td>
                                 <td><?= $p['tempat_perkawinan'] ?></td>
                                 <td>
                                    <span class="<?=
                                                   $p['status'] == 'Kawin' ? 'success' : ($p['status'] == 'Cerai' ? 'warning' : 'secondary')
                                                   ?>">
                                       <?= $p['status'] ?>
                                    </span>
                                 </td>
                                 <td>
                                    <div class="btn-group" role="group">
                                       <a href="<?= base_url('sekretaris/perkawinan/edit/' . $p['id']) ?>"
                                          class="btn btn-sm btn-warning"
                                          data-toggle="tooltip"
                                          title="Edit">
                                          <i class="fas fa-edit"></i>
                                       </a>
                                       <a href="<?= base_url('sekretaris/perkawinan/hapus/' . $p['id']) ?>"
                                          class="btn btn-sm btn-danger"
                                          onclick="return confirm('Yakin ingin menghapus?')">
                                          <i class="fas fa-trash"></i>
                                       </a>
                                    </div>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
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