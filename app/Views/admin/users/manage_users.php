<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manajemen User | Sistem Informasi Desa</title>
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
         <!-- Header -->
         <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
               <button class="toggle-sidebar btn btn-sm me-2" id="toggle-sidebar">
                  <i class="fas fa-bars"></i>
               </button>
               <h4 class="mb-0">Manajemen User</h4>
            </div>
         </nav>

         <!-- Content -->
         <div class="container-fluid">
            <?php if (session()->getFlashdata('message')) : ?>
               <div class="alert alert-success alert-dismissible fade show">
                  <?= session()->getFlashdata('message') ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </div>
            <?php endif; ?>

            <div class="card mb-4">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">Daftar User</h5>
                  <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary btn-sm">
                     <i class="fas fa-plus me-1"></i> Tambah User
                  </a>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                           <tr>
                              <th width="50">No</th>
                              <th>Username</th>
                              <th>Nama Lengkap</th>
                              <th>Email</th>
                              <th>Level</th>
                              <th>Status</th>
                              <th width="120">Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $no = 1; ?>
                           <?php foreach ($users as $user) : ?>
                              <tr>
                                 <td><?= $no++ ?></td>
                                 <td><?= esc($user['username']) ?></td>
                                 <td><?= esc($user['nama_lengkap']) ?></td>
                                 <td><?= esc($user['email']) ?></td>
                                 <td><?= ucfirst($user['level']) ?></td>
                                 <td>
                                    <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                       <?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                 </td>
                                 <td class="text-center">
                                    <div class="btn-group">
                                       <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                          <i class="fas fa-edit"></i>
                                       </a>
                                       <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin menghapus user ini?')">
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
      document.getElementById('toggle-sidebar').addEventListener('click', function() {
         document.getElementById('sidebar').classList.toggle('active');
         document.getElementById('main-content').classList.toggle('active');
      });

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