<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?> | Sistem Informasi Desa</title>
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
            <li><a href="<?= base_url('admin/manage_users') ?>"><i class="fas fa-users"></i> <span class="menu-text">Manajemen User</span></a></li>
            <li class="active"><a href="<?= base_url('admin/pejabat') ?>"><i class="fas fa-user-tie"></i> <span class="menu-text">Pejabat & Staf Desa</span></a></li>
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
            </div>
         </nav>

         <!-- Content -->
         <!-- Content -->
         <div class="container-fluid">
            <div class="card">
               <div class="card-header">
                  <h5 class="mb-0">Edit Pejabat & Staf Desa</h5>
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

                  <form action="<?= base_url('admin/pejabat/update/' . $pejabat['id']) ?>" method="post">
                     <?= csrf_field() ?>

                     <!-- Input Nama Lengkap -->
                     <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                           value="<?= esc($pejabat['nama_lengkap']) ?>" required>
                     </div>

                     <!-- Input Jabatan -->
                     <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan"
                           value="<?= esc($pejabat['jabatan']) ?>" required>
                     </div>

                     <!-- Periode -->
                     <div class="row mb-3">
                        <div class="col-md-6">
                           <label for="periode_mulai" class="form-label">Periode Mulai</label>
                           <input type="date" class="form-control" id="periode_mulai" name="periode_mulai"
                              value="<?= date('Y-m-d', strtotime($pejabat['periode_mulai'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                           <label for="periode_selesai" class="form-label">Periode Selesai (Opsional)</label>
                           <input type="date" class="form-control" id="periode_selesai" name="periode_selesai"
                              value="<?= $pejabat['periode_selesai'] ? date('Y-m-d', strtotime($pejabat['periode_selesai'])) : '' ?>">
                        </div>
                     </div>

                     <!-- Keterangan -->
                     <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= esc($pejabat['keterangan']) ?></textarea>
                     </div>

                     <!-- Info User Terkait (jika ada) -->
                     <?php if ($pejabat['user_id']) : ?>
                        <div class="mb-3">
                           <label class="form-label">Terhubung dengan User</label>
                           <?php
                           $userModel = new \App\Models\UserModel();
                           $user = $userModel->find($pejabat['user_id']);
                           ?>
                           <input type="text" class="form-control"
                              value="<?= esc($user['username'] ?? 'User tidak ditemukan') ?>" readonly>
                           <small class="text-muted">Untuk mengubah user terkait, silakan edit dari menu manajemen user</small>
                           <input type="hidden" name="user_id" value="<?= $pejabat['user_id'] ?>">
                        </div>
                     <?php endif; ?>

                     <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                     </button>
                     <a href="<?= base_url('admin/pejabat') ?>" class="btn btn-secondary">
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
   </script>
</body>

</html>