<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Data Penduduk - Sistem Informasi Desa</title>
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
            <li><a href="<?= base_url('kepala-desa/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>

            <li><a href="<?= base_url('kepala-desa/surat') ?>"><i class="fas fa-file-signature"></i> <span class="menu-text">Persetujuan Surat</span></a></li>

            <li class="active">
               <a href="#"><i class="fas fa-chart-bar"></i> <span class="menu-text">Statistik</span></a>
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
            <h5 class="mb-0">Statistik Desa</h5>
            <div class="user-info">
               <span><?= session('nama_lengkap') ?></span>
               <i class="fas fa-user-circle"></i>
            </div>
         </header>

         <!-- Content -->
         <div class="container-fluid p-4">
            <div class="card">
               <div class="card-header">
                  <div class="d-flex justify-content-between align-items-center">
                     <h5 class="mb-0"><?= $title ?></h5>
                     <div class="d-flex">
                        <div class="dropdown me-2">
                           <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                              <?= ucfirst($type) ?>
                           </button>
                           <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="<?= base_url('kepala-desa/statistik?type=penduduk') ?>">Penduduk</a></li>
                              <li><a class="dropdown-item" href="<?= base_url('kepala-desa/statistik?type=kelahiran') ?>">Kelahiran</a></li>
                              <li><a class="dropdown-item" href="<?= base_url('kepala-desa/statistik?type=kematian') ?>">Kematian</a></li>
                           </ul>
                        </div>
                        <select class="form-select" id="tahun-select">
                           <?php if ($type != 'penduduk') foreach ($years as $y): ?>
                              <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <?php if ($type == 'penduduk'): ?>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="card mb-4">
                              <div class="card-header">
                                 <h6>Statistik Pendidikan</h6>
                              </div>
                              <div class="card-body">
                                 <canvas id="pendidikanChart" height="250"></canvas>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="card mb-4">
                              <div class="card-header">
                                 <h6>Statistik Pekerjaan</h6>
                              </div>
                              <div class="card-body">
                                 <canvas id="pekerjaanChart" height="250"></canvas>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="card mb-4">
                              <div class="card-header">
                                 <h6>Distribusi Usia</h6>
                              </div>
                              <div class="card-body">
                                 <canvas id="usiaChart" height="250"></canvas>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="card mb-4">
                              <div class="card-header">
                                 <h6>Jenis Kelamin</h6>
                              </div>
                              <div class="card-body">
                                 <canvas id="genderChart" height="250"></canvas>
                              </div>
                           </div>
                        </div>

                        <div class="card-body">
                           <ul class="list-group list-group-flush">
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                 <div>
                                    <strong>Penduduk Usia Produktif (17-60 tahun)</strong>
                                    <div class="progress mt-2">
                                       <div class="progress-bar bg-success" role="progressbar"
                                          style="width: <?= $statistik['usia_produktif']['persentase'] ?>%"
                                          aria-valuenow="<?= $statistik['usia_produktif']['persentase'] ?>"
                                          aria-valuemin="0"
                                          aria-valuemax="100">
                                       </div>
                                    </div>
                                    <small class="text-muted">
                                       <?= number_format($statistik['usia_produktif']['produktif']) ?> dari <?= number_format($statistik['usia_produktif']['total']) ?> penduduk
                                    </small>
                                 </div>
                                 <span class="badge bg-primary rounded-pill">
                                    <?= $statistik['usia_produktif']['persentase'] ?>%
                                 </span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                 <div>
                                    <strong>Rasio Jenis Kelamin</strong>
                                    <div class="progress mt-2">
                                       <div class="progress-bar bg-info" role="progressbar"
                                          style="width: <?= $statistik['rasio_gender']['L']['persentase'] ?>%"
                                          aria-valuenow="<?= $statistik['rasio_gender']['L']['persentase'] ?>"
                                          aria-valuemin="0"
                                          aria-valuemax="100">
                                       </div>
                                       <div class="progress-bar bg-warning" role="progressbar"
                                          style="width: <?= $statistik['rasio_gender']['P']['persentase'] ?>%"
                                          aria-valuenow="<?= $statistik['rasio_gender']['P']['persentase'] ?>"
                                          aria-valuemin="0"
                                          aria-valuemax="100">
                                       </div>
                                    </div>
                                    <small class="text-muted">
                                       Laki-laki: <?= number_format($statistik['rasio_gender']['L']['jumlah']) ?> |
                                       Perempuan: <?= number_format($statistik['rasio_gender']['P']['jumlah']) ?>
                                    </small>
                                 </div>
                                 <span class="badge bg-primary rounded-pill">
                                    L : <?= $statistik['rasio_gender']['L']['persentase'] ?>% --- P : <?= $statistik['rasio_gender']['P']['persentase'] ?>%
                                 </span>
                              </li>
                           </ul>
                        </div>
                     </div>
               </div>



            <?php elseif ($type == 'kelahiran'): ?>
               <div class="row">
                  <div class="col-lg-8">
                     <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white text-black">
                           <div class="d-flex justify-content-between align-items-center">
                              <h6 class="mb-0">Statistik Kelahiran Tahun <?= $year ?></h6>
                              <span class="badge bg-secondary text-light">
                                 Total: <?= number_format($statistik['total']) ?> Kelahiran
                              </span>
                           </div>
                        </div>
                        <div class="card-body">
                           <canvas id="kelahiranChart" height="300"></canvas>
                           <div class="mt-3 text-center">
                              <small class="text-muted">
                                 Data kelahiran per bulan di Desa Konoha
                              </small>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white text-black">
                           <h6 class="mb-0">Distribusi Jenis Kelamin</h6>
                        </div>
                        <div class="card-body">
                           <canvas id="kelahiranGenderChart" height="250"></canvas>
                           <div class="mt-3 text-center">
                              <div class="row">
                                 <?php foreach ($statistik['jenis_kelamin'] as $jk): ?>
                                    <div class="col-6">
                                       <div class="p-2">
                                          <h5 class="mb-1 <?= $jk['jenis_kelamin'] == 'L' ? 'text-primary' : 'text-pink' ?>">
                                             <?= number_format($jk['jumlah']) ?>
                                          </h5>
                                          <small class="text-muted">
                                             <?= $jk['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                          </small>
                                       </div>
                                    </div>
                                 <?php endforeach; ?>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

            <?php elseif ($type == 'kematian'): ?>
               <div class="row">
                  <div class="col-lg-8">
                     <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white text-dark">
                           <div class="d-flex justify-content-between align-items-center">
                              <h6 class="mb-0">Statistik Kematian Tahun <?= $year ?></h6>
                              <span class="badge bg-secondary text-light">
                                 Total: <?= number_format($statistik['total']) ?> Kematian
                              </span>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="chart-container">
                              <canvas id="kematianChart" height="300"></canvas>
                           </div>
                           <div class="mt-3 text-center">
                              <small class="text-muted">
                                 <i class="fas fa-info-circle"></i> Data kematian bulanan di Desa Konoha tahun <?= $year ?>
                              </small>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white text-dark">
                           <h6 class="mb-0">Penyebab Utama Kematian</h6>
                        </div>
                        <div class="card-body">
                           <div class="chart-container">
                              <canvas id="penyebabChart" height="250"></canvas>
                           </div>
                           <div class="mt-3">
                              <ul class="list-group list-group-flush">
                                 <?php foreach ($statistik['penyebab'] as $index => $penyebab): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">

                                       <span class="flex-grow-1"><?= $penyebab['penyebab'] ?></span>
                                       <span class="badge bg-dark rounded-pill"><?= $penyebab['jumlah'] ?></span>
                                    </li>
                                 <?php endforeach; ?>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

            <?php endif; ?>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <script>
      $(document).ready(function() {
         $('.btn-reject').click(function() {
            const id = $(this).data('id');
            $('#rejectForm').attr('action', '/kepala-desa/surat/reject/' + id);
            $('#rejectModal').modal('show');
         });
      });
   </script>

   <script>
      const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
      // Tahun select
      document.getElementById('tahun-select').addEventListener('change', function() {
         const year = this.value;
         const url = new URL(window.location.href);
         url.searchParams.set('tahun', year);
         window.location.href = url.toString();
      });

      <?php if ($type == 'penduduk'): ?>
         // Pendidikan Chart
         new Chart(document.getElementById('pendidikanChart'), {
            type: 'bar',
            data: {
               labels: <?= json_encode(array_column($statistik['pendidikan'], 'pendidikan')) ?>,
               datasets: [{
                  label: 'Jumlah Penduduk',
                  data: <?= json_encode(array_column($statistik['pendidikan'], 'jumlah')) ?>,
                  backgroundColor: 'rgba(54, 162, 235, 0.7)'
               }]
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true,
                     ticks: {
                        precision: 0 // bilangan bulat
                     }
                  }
               }
            }
         });

         // Pekerjaan Chart
         new Chart(document.getElementById('pekerjaanChart'), {
            type: 'pie',
            data: {
               labels: <?= json_encode(array_column($statistik['pekerjaan'], 'pekerjaan')) ?>,
               datasets: [{
                  data: <?= json_encode(array_column($statistik['pekerjaan'], 'jumlah')) ?>,
                  backgroundColor: [
                     '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                  ]
               }]
            }
         });

         // Usia Chart
         new Chart(document.getElementById('usiaChart'), {
            type: 'line',
            data: {
               labels: <?= json_encode(array_map(fn($item) => $item['range_usia'] . '-' . ($item['range_usia'] + 9), $statistik['usia'])) ?>,
               datasets: [{
                  label: 'Jumlah Penduduk',
                  data: <?= json_encode(array_column($statistik['usia'], 'jumlah')) ?>,
                  borderColor: '#4BC0C0',
                  fill: true,
                  backgroundColor: 'rgba(75, 192, 192, 0.2)'
               }]
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true,
                     ticks: {
                        precision: 0,
                        stepSize: 1
                     }
                  },
                  x: {
                     title: {
                        display: true,
                     }
                  }
               },
               plugins: {
                  legend: {
                     display: true,
                     position: 'top'
                  }
               }
            }
         });

         // Gender Chart
         new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
               labels: <?= json_encode(array_map(fn($item) => $item['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan', $statistik['jenis_kelamin'])) ?>,
               datasets: [{
                  data: <?= json_encode(array_column($statistik['jenis_kelamin'], 'jumlah')) ?>,
                  backgroundColor: ['#36A2EB', '#FF6384'],
                  borderWidth: 1,
                  hoverOffset: 10
               }]
            },
            options: {
               responsive: true,
               maintainAspectRatio: false,
               plugins: {
                  legend: {
                     position: 'right',
                     labels: {
                        boxWidth: 12,
                        padding: 20,
                        font: {
                           size: 12
                        },
                        usePointStyle: true
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
               cutout: '65%',
               animation: {
                  animateScale: true,
                  animateRotate: true
               }
            }
         });

      <?php elseif ($type == 'kelahiran'): ?>
         const kelahiranData = Array(12).fill(0);
         <?php foreach ($statistik['bulanan'] as $item): ?>
            kelahiranData[<?= $item['bulan'] - 1 ?>] = <?= $item['jumlah'] ?>;
         <?php endforeach; ?>

         // Warna chart
         const ctx = document.getElementById('kelahiranChart').getContext('2d');
         const gradient = ctx.createLinearGradient(0, 0, 0, 300);
         gradient.addColorStop(0, 'rgba(75, 192, 192, 0.8)');
         gradient.addColorStop(1, 'rgba(75, 192, 192, 0.2)');

         // Chart Kelahiran
         new Chart(ctx, {
            type: 'bar',
            data: {
               labels: bulanLabels,
               datasets: [{
                  label: 'Jumlah Kelahiran',
                  data: kelahiranData,
                  backgroundColor: gradient,
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1,
                  borderRadius: 6,
                  hoverBackgroundColor: 'rgba(75, 192, 192, 1)',
                  barPercentage: 1,
                  categoryPercentage: 1
               }]
            },
            options: {
               responsive: true,
               layout: {
                  padding: {
                     top: 10,
                     right: 15,
                     bottom: 10,
                     left: 15
                  }
               },
               plugins: {
                  legend: {
                     display: false
                  },
                  tooltip: {
                     callbacks: {
                        label: function(context) {
                           return ` ${context.parsed.y} kelahiran`;
                        }
                     },
                     position: 'nearest',
                     backgroundColor: 'rgba(0,0,0,0.8)',
                     titleFont: {
                        size: 14,
                        weight: 'bold'
                     },
                     bodyFont: {
                        size: 12
                     },
                     padding: 12,
                     cornerRadius: 6,
                     displayColors: false
                  }
               },
               scales: {
                  y: {
                     beginAtZero: true,
                     ticks: {
                        callback: function(value) {
                           return Number(value) === value ? value : '';
                        },
                        padding: 10
                     },
                     grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawTicks: false
                     }
                  },
                  x: {
                     grid: {
                        display: false
                     },

                  }
               },
            }
         });

         // Chart Jenis Kelamin
         new Chart(document.getElementById('kelahiranGenderChart'), {
            type: 'doughnut',
            data: {
               labels: <?= json_encode(array_map(fn($item) => $item['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan', $statistik['jenis_kelamin'])) ?>,
               datasets: [{
                  data: <?= json_encode(array_column($statistik['jenis_kelamin'], 'jumlah')) ?>,
                  backgroundColor: [
                     'rgba(54, 162, 235, 0.8)',
                     'rgba(255, 99, 132, 0.8)'
                  ],
                  borderColor: [
                     'rgba(54, 162, 235, 1)',
                     'rgba(255, 99, 132, 1)'
                  ],
                  borderWidth: 1,
                  hoverOffset: 10
               }]
            },
            options: {
               responsive: true,
               cutout: '70%',
               plugins: {
                  legend: {
                     position: 'bottom',
                     labels: {
                        boxWidth: 12,
                        padding: 20,
                        font: {
                           size: 13
                        }
                     }
                  },
                  tooltip: {
                     callbacks: {
                        label: function(context) {
                           const total = context.dataset.data.reduce((a, b) => a + b, 0);
                           const value = context.parsed;
                           const percentage = Math.round((value / total) * 100);
                           return ` ${value} kelahiran (${percentage}%)`;
                        }
                     }
                  }
               }
            }
         });

      <?php elseif ($type == 'kematian'): ?>
         // Data kematian
         const kematianData = Array(12).fill(0);
         <?php foreach ($statistik['bulanan'] as $item): ?>
            kematianData[<?= $item['bulan'] - 1 ?>] = <?= $item['jumlah'] ?>;
         <?php endforeach; ?>

         // Chart Kematian
         const kematianCtx = document.getElementById('kematianChart').getContext('2d');
         new Chart(kematianCtx, {
            type: 'line',
            data: {
               labels: bulanLabels,
               datasets: [{
                  label: 'Jumlah Kematian',
                  data: kematianData,
                  borderColor: 'rgba(220, 53, 69, 0.8)',
                  backgroundColor: 'rgba(220, 53, 69, 0.1)',
                  borderWidth: 2,
                  tension: 0.3,
                  fill: true,
                  pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                  pointBorderColor: '#fff',
                  pointRadius: 5,
                  pointHoverRadius: 7,
                  pointHitRadius: 20
               }]
            },
            options: {
               responsive: true,
               plugins: {
                  legend: {
                     display: false
                  },
                  tooltip: {
                     callbacks: {
                        label: function(context) {
                           return ` ${context.parsed.y} kematian`;
                        }
                     }
                  }
               },
               scales: {
                  y: {
                     beginAtZero: true,
                     grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                     },
                     ticks: {
                        stepSize: 1
                     }
                  },
                  x: {
                     grid: {
                        display: false
                     }
                  }
               }
            }
         });

         // Chart Penyebab Kematian
         const penyebabCtx = document.getElementById('penyebabChart').getContext('2d');
         new Chart(penyebabCtx, {
            type: 'doughnut',
            data: {
               labels: <?= json_encode(array_column($statistik['penyebab'], 'penyebab')) ?>,
               datasets: [{
                  data: <?= json_encode(array_column($statistik['penyebab'], 'jumlah')) ?>,
                  backgroundColor: [
                     'rgba(220, 53, 69, 0.7)',
                     'rgba(23, 162, 184, 0.7)',
                     'rgba(40, 167, 69, 0.7)',
                     'rgba(255, 193, 7, 0.7)',
                     'rgba(0, 123, 255, 0.7)'
                  ],
                  borderColor: [
                     'rgba(220, 53, 69, 1)',
                     'rgba(23, 162, 184, 1)',
                     'rgba(40, 167, 69, 1)',
                     'rgba(255, 193, 7, 1)',
                     'rgba(0, 123, 255, 1)'
                  ],
                  borderWidth: 1,
                  hoverOffset: 10
               }]
            },
            options: {
               responsive: true,
               cutout: '65%',
               plugins: {
                  legend: {
                     position: 'right',
                     labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                           size: 12
                        },
                        usePointStyle: true
                     }
                  }
               }
            }
         });
      <?php endif; ?>
   </script>
   <script>
      // Toggle Sidebar
      const toggleSidebar = () => {
         const sidebar = document.getElementById('sidebar');
         const content = document.getElementById('main-content');

         sidebar.classList.toggle('active');
         content.classList.toggle('active');

         // Simpan state
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

      // sidebar
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

      // Responsive sidebar
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
   </script>
</body>

</html>