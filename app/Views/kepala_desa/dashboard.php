<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?></title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/css/kadesDashboard.css') ?>">
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
            <li class="active"><a href="<?= base_url('kepala-desa/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>

            <li><a href="<?= base_url('kepala-desa/surat') ?>"><i class="fas fa-file-signature"></i> <span class="menu-text">Persetujuan Surat</span></a></li>

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
            <h5 class="mb-0">Dashboard Kepala Desa</h5>
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
                        <span class="small text-white">Data Terakhir</span>
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
                        <a class="small text-white stretched-link" href="<?= base_url('kepala-desa/statistik?type=kelahiran') ?>">Lihat Statistik</a>
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
                        <a class="small text-white stretched-link" href="<?= base_url('kepala-desa/statistik?type=kematian') ?>">Lihat Statistik</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-danger text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Surat Menunggu Persetujuan</h5>
                        <h2><?= $surat_pending ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('kepala-desa/surat') ?>">Proses Sekarang</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
               <div class="col-md-6">
                  <div class="card mb-4">
                     <div class="card-header">
                        <h5>Statistik Pendidikan Penduduk</h5>
                     </div>
                     <div class="card-body">
                        <canvas id="pendidikanChart" height="250"></canvas>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card mb-4">
                     <div class="card-header">
                        <h5>Statistik Pekerjaan</h5>
                     </div>
                     <div class="card-body">
                        <canvas id="pekerjaanChart" height="250"></canvas>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
               <div class="col-md-6">
                  <div class="card mb-4">
                     <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Surat Menunggu Persetujuan</h5>
                        <a href="<?= base_url('kepala-desa/surat') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                     </div>
                     <div class="card-body">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead>
                                 <tr>
                                    <th>No</th>
                                    <th>Jenis Surat</th>
                                    <th>Pemohon</th>
                                    <th>Aksi</th>
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
                                          <a href="<?= base_url('kepala-desa/surat/approve/' . $surat['id']) ?>" class="btn btn-sm btn-success">Setujui</a>
                                          <a href="<?= base_url('kepala-desa/surat/reject/' . $surat['id']) ?>" class="btn btn-sm btn-danger">Tolak</a>
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
                        <h5>Statistik Kependudukan</h5>
                     </div>
                     <div class="card-body">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item d-flex justify-content-between align-items-center">
                              <div>
                                 <strong>Penduduk Usia Produktif (17-60 Tahun)</strong>
                                 <div class="progress mt-2">
                                    <div class="progress-bar bg-success" role="progressbar"
                                       style="width: <?= round($usia_produktif['persentase']) ?>%"
                                       aria-valuenow="<?= round($usia_produktif['persentase']) ?>"
                                       aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                 </div>
                                 <small class="text-muted">
                                    <?= $usia_produktif['produktif'] ?> dari <?= $usia_produktif['total'] ?> penduduk
                                 </small>
                              </div>
                              <span class="badge bg-primary rounded-pill">
                                 <?= round($usia_produktif['persentase']) ?>%
                              </span>
                           </li>
                           <li class="list-group-item d-flex justify-content-between align-items-center">
                              <div>
                                 <strong>Rasio Jenis Kelamin</strong>
                                 <div class="progress mt-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                       style="width: <?= $statistik_jk['persen_laki'] ?>%"
                                       title="Laki-laki: <?= $statistik_jk['laki'] ?> orang">
                                    </div>
                                    <div class="progress-bar bg-warning" role="progressbar"
                                       style="width: <?= $statistik_jk['persen_perempuan'] ?>%"
                                       title="Perempuan: <?= $statistik_jk['perempuan'] ?> orang">
                                    </div>
                                 </div>
                              </div>
                              <span class="badge bg-primary rounded-pill">
                                 L:<?= $statistik_jk['persen_laki'] ?>% --- P:<?= $statistik_jk['persen_perempuan'] ?>%
                              </span>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <!-- Tambahkan Chart.js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
      // Pendidikan Chart
      const pendidikanCtx = document.getElementById('pendidikanChart').getContext('2d');
      const pendidikanChart = new Chart(pendidikanCtx, {
         type: 'bar',
         data: {
            labels: <?= json_encode(array_column($statistik_pendidikan, 'pendidikan')) ?>,
            datasets: [{
               label: 'Jumlah Penduduk',
               data: <?= json_encode(array_column($statistik_pendidikan, 'jumlah')) ?>,
               backgroundColor: 'rgba(54, 162, 235, 0.7)',
               borderColor: 'rgba(54, 162, 235, 1)',
               borderWidth: 1
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false, // Tambahkan ini untuk kontrol rasio yang lebih baik
            scales: {
               y: {
                  beginAtZero: true,
                  ticks: {
                     precision: 0, // Memastikan tidak ada desimal
                     callback: function(value) {
                        if (value % 1 === 0) { // Hanya menampilkan jika integer
                           return value;
                        }
                     }
                  },
                  // Menyesuaikan step size secara otomatis
                  afterDataLimits: function(scale) {
                     scale.options.ticks.stepSize = Math.max(1, Math.floor((scale.max - scale.min) / 5));
                  }
               },
               x: {
                  grid: {
                     display: false // Hilangkan grid lines pada sumbu X
                  }
               }
            },
            plugins: {
               tooltip: {
                  callbacks: {
                     label: function(context) {
                        return `${context.dataset.label}: ${Math.round(context.raw)}`;
                     }
                  }
               }
            }
         }
      });

      // Pekerjaan Chart
      // Ganti kode pekerjaanChart dengan ini:
      const pekerjaanCtx = document.getElementById('pekerjaanChart').getContext('2d');
      const pekerjaanChart = new Chart(pekerjaanCtx, {
         type: 'doughnut', // Ganti dari pie ke doughnut untuk tampilan lebih modern
         data: {
            labels: <?= json_encode(array_column($statistik_pekerjaan, 'pekerjaan')) ?>,
            datasets: [{
               label: 'Jumlah Penduduk',
               data: <?= json_encode(array_column($statistik_pekerjaan, 'jumlah')) ?>,
               backgroundColor: [
                  'rgba(255, 99, 132, 0.7)',
                  'rgba(54, 162, 235, 0.7)',
                  'rgba(255, 206, 86, 0.7)',
                  'rgba(75, 192, 192, 0.7)',
                  'rgba(153, 102, 255, 0.7)',
                  'rgba(255, 159, 64, 0.7)',
                  'rgba(199, 199, 199, 0.7)'
               ],
               borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(199, 199, 199, 1)'
               ],
               borderWidth: 1,
               hoverOffset: 15 // Efek hover lebih jelas
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  position: 'right', // Pindah legend ke kanan
                  labels: {
                     boxWidth: 12,
                     padding: 20,
                     font: {
                        size: 12
                     },
                     usePointStyle: true // Gunakan titik kecil bukan kotak
                  }
               },
               tooltip: {
                  callbacks: {
                     label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        return `${label}: ${value} orang`;
                     }
                  }
               }
            },
            cutout: '45%', // Tingkatkan cutout untuk doughnut
            animation: {
               animateScale: true,
               animateRotate: true
            }
         }
      });
   </script>

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