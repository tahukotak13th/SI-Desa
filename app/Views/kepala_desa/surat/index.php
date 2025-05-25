<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Persetujuan Surat - Sistem Informasi Desa</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/css/kadesDashboard.css') ?>">
   <style>
      .preview-surat {
         background-color: #f8f9fa;
         border: 1px solid #dee2e6;
         border-radius: 5px;
         padding: 20px;
         font-family: 'Times New Roman', Times, serif;
         white-space: pre-line;
      }

      .modal-lg {
         max-width: 90%;
      }
   </style>
</head>

<body>
   <div class="sekretaris-wrapper">
      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
         <div class="sidebar-header">
            <h4>SID</h4>
            <p>Kepala Desa</p>
         </div>
         <ul class="sidebar-menu">
            <li><a href="<?= base_url('kepala-desa/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>
            <li class="active"><a href="<?= base_url('kepala-desa/surat') ?>"><i class="fas fa-file-signature"></i> <span class="menu-text">Persetujuan Surat</span></a></li>
            <li class="menu-dropdown">
               <a href="#"><i class="fas fa-chart-bar"></i> <span class="menu-text">Statistik</span> <i class="fas fa-chevron-down dropdown-icon"></i></a>
               <ul class="submenu" style="list-style: none;">
                  <li><a href="<?= base_url('kepala-desa/statistik') ?>?type=penduduk"><i class="fas fa-user"></i> Statistik Penduduk</a></li>
                  <li><a href="<?= base_url('kepala-desa/statistik') ?>?type=kelahiran"><i class="fas fa-baby"></i> Statistik Kelahiran</a></li>
                  <li><a href="<?= base_url('kepala-desa/statistik') ?>?type=kematian"><i class="fas fa-skull"></i> Statistik Kematian</a></li>
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
            <h5 class="mb-0">Persetujuan Surat Keterangan</h5>
            <div class="user-info">
               <span><?= session('nama_lengkap') ?></span>
               <i class="fas fa-user-circle"></i>
            </div>
         </header>

         <!-- Content -->
         <div class="container-fluid p-4">
            <div class="card shadow-sm">
               <div class="card-header text-dark">
                  <h5 class="mb-0">Daftar Surat Menunggu Persetujuan</h5>
               </div>
               <div class="card-body">
                  <?php if (session()->getFlashdata('success')): ?>
                     <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                     </div>
                  <?php endif; ?>

                  <div class="table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead class="table-light">
                           <tr>
                              <th width="5%">No</th>
                              <th width="15%">No. Surat</th>
                              <th width="15%">Jenis Surat</th>
                              <th width="20%">Pemohon</th>
                              <th width="15%">Tanggal</th>
                              <th width="30%">Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($surat_menunggu)): ?>
                              <?php foreach ($surat_menunggu as $index => $surat): ?>
                                 <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($surat['no_surat']) ?></td>
                                    <td><?= esc($surat['nama_surat']) ?></td>
                                    <td><?= esc($surat['nama_penduduk']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($surat['tanggal_pengajuan'])) ?></td>
                                    <td>
                                       <!-- Tombol Preview -->
                                       <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                          data-bs-target="#previewModal<?= $surat['id'] ?>">
                                          <i class="fas fa-eye"></i> Preview
                                       </button>

                                       <!-- Form Approve -->
                                       <form action="<?= base_url('kepala-desa/surat/approve/' . $surat['id']) ?>"
                                          method="post" class="d-inline">
                                          <?= csrf_field() ?>
                                          <button type="submit" class="btn btn-sm btn-success"
                                             onclick="return confirm('Setujui surat ini?')">
                                             <i class="fas fa-check"></i> Setujui
                                          </button>
                                       </form>

                                       <!-- Tombol Reject dengan Modal -->
                                       <a href="<?= base_url(route_to('kepala_desa.surat.reject', $surat['id'])) ?>"
                                          class="btn btn-sm btn-danger"
                                          onclick="return confirm('Tolak surat ini?')">
                                          <i class="fas fa-times"></i> Tolak
                                       </a>

                                       <!-- Modal Preview -->
                                       <div class="modal fade" id="previewModal<?= $surat['id'] ?>" tabindex="-1" aria-hidden="true">
                                          <div class="modal-dialog modal-lg">
                                             <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                   <h5 class="modal-title">Preview Surat: <?= esc($surat['no_surat']) ?></h5>
                                                   <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                   <div class="preview-surat">
                                                      <?= nl2br(esc($surat['isi_surat'])) ?>
                                                   </div>
                                                </div>
                                                <div class="modal-footer">
                                                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           <?php else: ?>
                              <tr>
                                 <td colspan="6" class="text-center text-muted">Tidak ada surat yang menunggu persetujuan</td>
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

      // Inisialisasi tooltip
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function(tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl);
      });
   </script>
</body>

</html>