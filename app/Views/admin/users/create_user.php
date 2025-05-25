<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah User | Sistem Informasi Desa</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <link rel="stylesheet" href="<?= base_url('assets/css/adminDashboard.css') ?>">

   <style>
      #main-content {
         padding-top: 0;
      }
   </style>
</head>

<body>
   <div class="admin-wrapper">
      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
         <div class="sidebar-header">
            <h4>SID</h4>
         </div>
         <ul class="sidebar-menu">
            <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>
            <li class="active"><a href="<?= base_url('admin/manage_users') ?>"><i class="fas fa-users"></i> <span class="menu-text">Manajemen User</span></a></li>
            <li><a href="<?= base_url('admin/manage_pejabat') ?>"><i class="fas fa-user-tie"></i> <span class="menu-text">Pejabat & Staf Desa</span></a></li>
            <li><a href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Logout</span></a></li>
         </ul>
      </div>

      <!-- Main Content -->
      <div class="main-content" id="main-content">
         <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
               <button class="toggle-sidebar btn btn-sm me-2" id="toggle-sidebar">
                  <i class="fas fa-bars"></i>
               </button>
               <!-- <h4 class="mb-0">Manajemen User</h4> -->
            </div>
         </nav>

         <!-- Content -->
         <div class="container-fluid">
            <div class="card">
               <div class="card-header">
                  <h5 class="mb-0">Tambah User Baru</h5>
               </div>
               <div class="card-body">
                  <?php if (session()->getFlashdata('errors')) : ?>
                     <div class="alert alert-danger">
                        <ul class="mb-0">
                           <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                              <li><?= esc($error) ?></li>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                  <?php endif; ?>

                  <form action="<?= base_url('admin/users/store') ?>" method="post">
                     <?= csrf_field() ?>

                     <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                     </div>

                     <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                     </div>

                     <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                     </div>

                     <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                     </div>

                     <div class="mb-3">
                        <label for="level" class="form-label">Level User</label>
                        <select class="form-select" id="level" name="level" required>
                           <option value="admin">Admin</option>
                           <option value="sekretaris">Sekretaris</option>
                           <option value="kepala_desa">Kepala Desa</option>
                        </select>
                     </div>

                     <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                     </button>
                     <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                     </a>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
      // Toggle Sidebar
      document.getElementById('toggle-sidebar').addEventListener('click', function() {
         document.getElementById('sidebar').classList.toggle('active');
         document.getElementById('main-content').classList.toggle('active');
      });

      // cek state sidebar
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