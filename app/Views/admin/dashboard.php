<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?></title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/css/adminDashboard.css') ?>">
   <!-- <link rel="stylesheet" href="public/assets/css/adminDashboard.css"> -->

</head>

<body>
   <div class="admin-wrapper">
      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
         <div class="sidebar-header">
            <h4>SID</h4>
         </div>
         <ul class="sidebar-menu">
            <li class="active"><a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>
            <li><a href="<?= base_url('admin/manage_users') ?>"><i class="fas fa-users"></i> <span class="menu-text">Manajemen User</span></a></li>
            <li><a href="<?= base_url('admin/manage_pejabat') ?>"><i class="fas fa-user-tie"></i> <span class="menu-text">Pejabat & Staf Desa</span></a></li>
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
            <h5 class="mb-0">Dashboard Admin</h5>
         </header>

         <!-- Content -->
         <div class="container-fluid p-4">
            <div class="row">
               <div class="col-md-4">
                  <div class="card bg-primary text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Total User</h5>
                        <h2><?= $total_users ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('admin/manage_users') ?>">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="card bg-success text-white mb-4">
                     <div class="card-body">
                        <h5 class="card-title">Pejabat & Staf Desa</h5>
                        <h2><?= $total_pejabat ?></h2>
                     </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= base_url('admin/manage_pejabat') ?>">Lihat Detail</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Tabel User -->
            <div class="card mb-4">
               <div class="card-header">
                  <h5>Daftar User</h5>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Username</th>
                              <th>Nama Lengkap</th>
                              <th>Status (Level)</th>
                              <!-- <th></th> -->
                           </tr>
                        </thead>
                        <tbody>
                           <?php $no = 1; ?>
                           <?php foreach ($users as $user) : ?>
                              <tr>
                                 <td><?= $no++ ?></td>
                                 <td><?= esc($user['username']) ?></td>
                                 <td><?= esc($user['nama_lengkap']) ?></td>
                                 <td><?= ucfirst(esc($user['level'])) ?></td>
                                 <!-- <td>
                                    <a href="<?= base_url('admin/editUser/' . $user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('admin/deleteUser/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                 </td> -->
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

         // Simpan state
         const isCollapsed = sidebar.classList.contains('active');
         localStorage.setItem('sidebarCollapsed', isCollapsed);
      };

      // state sidebar
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