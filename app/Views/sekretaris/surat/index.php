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
            <li class="menu-dropdown">
               <a href="#"><i class="fas fa-users"></i> <span class="menu-text">Data Kependudukan</span> <i class="fas fa-chevron-down dropdown-icon"></i></a>
               <ul class="submenu" style="list-style: none;">
                  <li><a href="<?= base_url('sekretaris/penduduk/') ?>"><i class="fas fa-user"></i> Data Penduduk</a></li>
                  <li><a href="<?= base_url('sekretaris/kelahiran') ?>"><i class="fas fa-baby"></i> Kelahiran</a></li>
                  <li><a href="<?= base_url('sekretaris/kematian') ?>"><i class="fas fa-skull"></i> Kematian</a></li>
                  <li><a href="<?= base_url('sekretaris/perkawinan') ?>"><i class="fas fa-heart"></i> Perkawinan</a></li>
               </ul>
            </li>
            <li class="active"><a href="<?= base_url('sekretaris/surat') ?>"><i class="fas fa-file-alt"></i> <span class="menu-text">Surat-surat Keterangan</span></a></li>
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
            <h5 class="mb-0">Surat Keterangan</h5>
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

            <div class="card shadow mb-4">
               <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
               </div>
               <div class="card-body">
                  <!-- Tab Navigation -->
                  <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                     <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button">
                           <i class="fas fa-plus-circle"></i> Buat Surat Baru
                        </button>
                     </li>
                     <li class="nav-item" role="presentation">
                        <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button">
                           <i class="fas fa-list"></i> Daftar Surat Saya
                        </button>
                     </li>
                  </ul>

                  <div class="tab-content" id="myTabContent">
                     <!-- Tab Buat Surat Baru -->
                     <div class="tab-pane fade show active" id="create" role="tabpanel">
                        <div class="row">
                           <?php foreach ($jenis_surat as $js): ?>
                              <div class="col-md-4 mb-4">
                                 <div class="card h-100">
                                    <div class="card-body text-center">
                                       <h5 class="card-title"><?= $js['nama_surat'] ?></h5>
                                       <p class="card-text">Kode: <?= $js['kode_surat'] ?></p>
                                       <a href="<?= base_url('sekretaris/surat/penduduk?jenis=' . $js['kode_surat']) ?>"
                                          class="btn btn-primary">
                                          <i class="fas fa-file-alt"></i> Buat Surat
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>

                     <!-- Tab Daftar Surat -->
                     <div class="tab-pane fade" id="list" role="tabpanel">
                        <div class="table-responsive">
                           <table class="table table-bordered table-hover">
                              <thead class="table-light">
                                 <tr>
                                    <th>No</th>
                                    <th>Jenis Surat</th>
                                    <th>No. Surat</th>
                                    <th>Pemohon</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php if (!empty($surat_list)): ?>
                                    <?php foreach ($surat_list as $index => $surat): ?>
                                       <tr>
                                          <td><?= $index + 1 ?></td>
                                          <td><?= esc($surat['nama_surat']) ?></td>
                                          <td><?= esc($surat['no_surat']) ?></td>
                                          <td><?= esc($surat['nama_penduduk']) ?></td>
                                          <td>
                                             <span class="badge bg-<?=
                                                                     $surat['status'] == 'disetujui' ? 'success' : ($surat['status'] == 'ditolak' ? 'danger' : 'warning')
                                                                     ?>">
                                                <?= ucfirst(esc($surat['status'])) ?>
                                             </span>
                                          </td>
                                          <td><?= date('d/m/Y', strtotime($surat['tanggal_pengajuan'])) ?></td>
                                          <td>
                                             <?php if ($surat['status'] == 'disetujui'): ?>
                                                <a href="<?= base_url('sekretaris/surat/cetak/' . $surat['id']) ?>"
                                                   class="btn btn-sm btn-success" title="Cetak Surat">
                                                   <i class="fas fa-print"></i>
                                                </a>
                                             <?php elseif ($surat['status'] == 'ditolak'): ?>
                                             <?php else: ?>
                                                <span class="text-muted">Menunggu approval</span>
                                             <?php endif; ?>
                                          </td>
                                       </tr>
                                    <?php endforeach; ?>
                                 <?php else: ?>
                                    <tr>
                                       <td colspan="7" class="text-center text-muted">Belum ada surat yang dibuat</td>
                                    </tr>
                                 <?php endif; ?>
                              </tbody>
                           </table>
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

         // Save state 
         const isCollapsed = sidebar.classList.contains('active');
         localStorage.setItem('sidebarCollapsed', isCollapsed);
      };

      // Initialize state 
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

      // Toggle Submenu
      document.querySelectorAll('.menu-dropdown > a').forEach(item => {
         item.addEventListener('click', function(e) {
            if (window.innerWidth >= 992) {
               e.preventDefault();
               const submenu = this.nextElementSibling;
               const dropdownIcon = this.querySelector('.dropdown-icon');

               submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
               dropdownIcon.classList.toggle('fa-chevron-down');
               dropdownIcon.classList.toggle('fa-chevron-up');
            }
         });
      });
   </script>

   <script>
      // Aktifkan tab dan simpan state
      document.addEventListener('DOMContentLoaded', function() {
         // Aktifkan tab bootstrap
         var tabElms = [].slice.call(document.querySelectorAll('button[data-bs-toggle="tab"]'));
         tabElms.forEach(function(tabEl) {
            tabEl.addEventListener('click', function() {
               localStorage.setItem('activeSuratTab', this.getAttribute('data-bs-target'));
            });
         });

         // Set tab aktif saat load
         var activeTab = localStorage.getItem('activeSuratTab');
         if (activeTab) {
            var tab = new bootstrap.Tab(document.querySelector('[data-bs-target="' + activeTab + '"]'));
            tab.show();
         }
      });
   </script>
</body>

</html>