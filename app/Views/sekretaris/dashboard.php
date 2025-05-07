<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?></title>
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
            <li class="active"><a href="<?= base_url('sekretaris/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>

            <!-- Data Kependudukan Menu -->
            <li class="menu-dropdown">
               <a href="#"><i class="fas fa-users"></i> <span class="menu-text">Data Kependudukan</span> <i class="fas fa-chevron-down dropdown-icon"></i></a>
               <ul class="submenu">
                  <li><a href="<?= base_url('sekretaris/penduduk/') ?>"><i class="fas fa-user"></i> Data Penduduk</a></li>
                  <li><a href="<?= base_url('sekretaris/pendidikan') ?>"><i class="fas fa-graduation-cap"></i> Pendidikan Penduduk</a></li>
                  <li><a href="<?= base_url('sekretaris/kelahiran') ?>"><i class="fas fa-baby"></i> Kelahiran</a></li>
                  <li><a href="<?= base_url('sekretaris/kematian') ?>"><i class="fas fa-cross"></i> Kematian</a></li>
                  <li><a href="<?= base_url('sekretaris/perkawinan') ?>"><i class="fas fa-heart"></i> Perkawinan</a></li>
               </ul>
            </li>

            <!-- Layanan Administrasi Menu -->
            <li class="menu-dropdown">
               <a href="#"><i class="fas fa-file-alt"></i> <span class="menu-text">Layanan Administrasi</span> <i class="fas fa-chevron-down dropdown-icon"></i></a>
               <ul class="submenu">
                  <li><a href="<?= base_url('sekretaris/surat/domisili') ?>"><i class="fas fa-file"></i> SK Domisili</a></li>
                  <li><a href="<?= base_url('sekretaris/surat/tidak_mampu') ?>"><i class="fas fa-file"></i> SK Tidak Mampu</a></li>
                  <li><a href="<?= base_url('sekretaris/surat/penghasilan') ?>"><i class="fas fa-file"></i> SK Penghasilan</a></li>
                  <li><a href="<?= base_url('sekretaris/surat/kematian') ?>"><i class="fas fa-file"></i> SK Kematian</a></li>
                  <li><a href="<?= base_url('sekretaris/surat/status_pekerjaan') ?>"><i class="fas fa-file"></i> SK Status Pekerjaan</a></li>
               </ul>
            </li>

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
            <h5 class="mb-0">Dashboard Sekretaris Desa</h5>
            <div class="user-info">
               <span><?= session('nama_lengkap') ?></span>
               <i class="fas fa-user-circle"></i>
            </div>
         </header>

         <!-- Content -->
         <div class="container-fluid p-4">
            <!-- Quick Stats -->
            <div class="row">
               <div class="col-md-3">
                  <div class="card bg-primary text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Total Penduduk</h5>
                        <h2><?= $total_penduduk ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('sekretaris/penduduk') ?>">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-success text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Kelahiran (Bulan Ini)</h5>
                        <h2><?= $kelahiran_bulan_ini ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('sekretaris/kelahiran') ?>">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-warning text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Kematian (Bulan Ini)</h5>
                        <h2><?= $kematian_bulan_ini ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('sekretaris/kematian') ?>">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-info text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Surat Menunggu Persetujuan</h5>
                        <h2><?= $surat_pending ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('sekretaris/surat') ?>">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
               <div class="col-md-6">
                  <div class="card mb-4">
                     <div class="card-header">
                        <h5>Surat Terbaru</h5>
                     </div>
                     <div class="card-body">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th>No</th>
                                    <th>Jenis Surat</th>
                                    <th>Pemohon</th>
                                    <th>Status</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $no = 1; ?>
                                 <?php foreach ($surat_terbaru as $surat) : ?>
                                    <tr>
                                       <td><?= $no++ ?></td>
                                       <td><?= esc($surat['nama_surat']) ?></td>
                                       <td><?= esc($surat['nama_penduduk']) ?></td>
                                       <td>
                                          <span class="badge bg-<?=
                                                                  $surat['status'] == 'disetujui' ? 'success' : ($surat['status'] == 'ditolak' ? 'danger' : ($surat['status'] == 'diajukan' ? 'warning' : 'secondary'))
                                                                  ?>">
                                             <?= ucfirst(esc($surat['status'])) ?>
                                          </span>
                                       </td>
                                    </tr>
                                 <?php endforeach; ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="card mb-4">
                     <div class="card-header">
                        <h5>Peristiwa Kependudukan Terbaru</h5>
                     </div>
                     <div class="card-body">
                        <ul class="list-group list-group-flush">
                           <?php foreach ($peristiwa_terbaru as $peristiwa) : ?>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                 <div>
                                    <strong><?= esc($peristiwa['jenis']) ?></strong>: <?= esc($peristiwa['nama']) ?>
                                    <br>
                                    <small class="text-muted"><?= date('d M Y', strtotime($peristiwa['tanggal'])) ?></small>
                                 </div>
                                 <span class="badge bg-primary rounded-pill">
                                    <?= $peristiwa['jenis'] == 'Kelahiran' ? '<i class="fas fa-baby"></i>' : ($peristiwa['jenis'] == 'Kematian' ? '<i class="fas fa-cross"></i>' :
                                       '<i class="fas fa-heart"></i>') ?>
                                 </span>
                              </li>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
               <div class="col-12">
                  <div class="card">
                     <div class="card-header">
                        <h5>Quick Actions</h5>
                     </div>
                     <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                           <a href="<?= base_url('sekretaris/penduduk/tambah') ?>" class="btn btn-primary">
                              <i class="fas fa-user-plus"></i> Tambah Penduduk
                           </a>
                           <a href="<?= base_url('sekretaris/surat/domisili/create') ?>" class="btn btn-success">
                              <i class="fas fa-file-alt"></i> Buat SK Domisili
                           </a>
                           <a href="<?= base_url('sekretaris/surat/tidak_mampu/buat') ?>" class="btn btn-info">
                              <i class="fas fa-file-alt"></i> Buat SK Tidak Mampu
                           </a>
                           <a href="<?= base_url('sekretaris/kelahiran/tambah') ?>" class="btn btn-warning">
                              <i class="fas fa-baby"></i> Catat Kelahiran
                           </a>
                           <a href="<?= base_url('sekretaris/kematian/tambah') ?>" class="btn btn-dark">
                              <i class="fas fa-cross"></i> Catat Kematian
                           </a>
                        </div>
                     </div>
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

         // Simpan state di localStorage
         const isCollapsed = sidebar.classList.contains('active');
         localStorage.setItem('sidebarCollapsed', isCollapsed);
      };

      // Toggle Submenu
      document.querySelectorAll('.menu-dropdown > a').forEach(item => {
         item.addEventListener('click', function(e) {
            if (window.innerWidth >= 992) {
               e.preventDefault();
               const submenu = this.nextElementSibling;
               const dropdownIcon = this.querySelector('.dropdown-icon');

               this.parentElement.classList.toggle('active');
               submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
               dropdownIcon.classList.toggle('fa-chevron-down');
               dropdownIcon.classList.toggle('fa-chevron-up');
            }
         });
      });

      // Init state dari localStorage
      document.addEventListener('DOMContentLoaded', () => {
         const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
         const sidebar = document.getElementById('sidebar');
         const content = document.getElementById('main-content');

         if (isCollapsed && window.innerWidth >= 992) {
            sidebar.classList.add('active');
            content.classList.add('active');
         }

         document.getElementById('toggle-sidebar').addEventListener('click', toggleSidebar);

         // Set active menu based on current URL
         const currentUrl = window.location.href;
         const menuItems = document.querySelectorAll('.sidebar-menu li a');

         menuItems.forEach(item => {
            if (item.href === currentUrl) {
               item.parentElement.classList.add('active');
               // Open parent dropdown if exists
               const parentDropdown = item.closest('.submenu');
               if (parentDropdown) {
                  parentDropdown.style.display = 'block';
                  parentDropdown.previousElementSibling.querySelector('.dropdown-icon').classList.add('fa-chevron-up');
                  parentDropdown.previousElementSibling.querySelector('.dropdown-icon').classList.remove('fa-chevron-down');
                  parentDropdown.parentElement.classList.add('active');
               }
            }
         });
      });

      // Responsive behavior
      window.addEventListener('resize', () => {
         const sidebar = document.getElementById('sidebar');
         const content = document.getElementById('main-content');

         if (window.innerWidth < 992) {
            sidebar.classList.remove('active');
            content.classList.remove('active');
            // Hide all submenus on mobile
            document.querySelectorAll('.submenu').forEach(submenu => {
               submenu.style.display = 'none';
            });
         } else {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
               sidebar.classList.add('active');
               content.classList.add('active');
            } else {
               sidebar.classList.remove('active');
               content.classList.remove('active');
            }
         }
      });
   </script>
</body>

</html>