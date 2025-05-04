<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit User | Sistem Informasi Desa</title>
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
            <li><a href="<?= base_url('admin/pejabat/index') ?>"><i class="fas fa-user-tie"></i> <span class="menu-text">Pejabat Desa</span></a></li>
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
                  <h5 class="mb-0">Edit User</h5>
               </div>
               <div class="card-body">
                  <?php if (session()->getFlashdata('message')) : ?>
                     <div class="alert alert-success">
                        <?= session()->getFlashdata('message') ?>
                     </div>
                  <?php endif; ?>

                  <?php if (session()->getFlashdata('errors')) : ?>
                     <div class="alert alert-danger">
                        <ul class="mb-0">
                           <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                              <li><?= esc($error) ?></li>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                  <?php endif; ?>

                  <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                     <?= csrf_field() ?>

                     <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                           value="<?= esc($user['username']) ?>" required>
                     </div>

                     <div class="mb-3">
                        <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" class="form-control" id="password" name="password">
                     </div>

                     <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                           value="<?= esc($user['nama_lengkap']) ?>" required>
                     </div>

                     <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                           value="<?= esc($user['email']) ?>" required>
                     </div>

                     <div class="mb-3">
                        <label for="level" class="form-label">Level User</label>
                        <select class="form-select" id="level" name="level" required>
                           <option value="admin" <?= $user['level'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                           <option value="sekretaris" <?= $user['level'] === 'sekretaris' ? 'selected' : '' ?>>Sekretaris</option>
                           <option value="kepala_desa" <?= $user['level'] === 'kepala_desa' ? 'selected' : '' ?>>Kepala Desa</option>
                        </select>
                     </div>

                     <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                           <?= $user['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Aktif</label>
                     </div>

                     <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                     </button>
                     <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                     </a>
                  </form>
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
            });

            // Responsive behavior
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

            // Set active menu based on current URL
            document.addEventListener('DOMContentLoaded', function() {
               const currentUrl = window.location.href;
               const menuItems = document.querySelectorAll('.sidebar-menu li a');

               menuItems.forEach(item => {
                  if (item.href === currentUrl) {
                     item.parentElement.classList.add('active');
                  }
               });
            });
         </script>
</body>

</html>