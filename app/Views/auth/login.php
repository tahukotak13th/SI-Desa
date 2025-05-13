<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?></title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
      body {
         background-color: #f8f9fa;
         background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
         background-size: cover;
         background-position: center;
         height: 100vh;
      }

      .login-container {
         background-color: rgba(255, 255, 255, 0.9);
         border-radius: 10px;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
         padding: 30px;
         margin-top: 100px;
      }


      #logo-desa {
         height: 100px;
         width: auto;
         margin-right: 20px;
         margin-left: 20px;
      }
   </style>
</head>

<body>
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-5">
            <div class="login-container">
               <div class="text-center mb-4">
                  <!-- <img src="<?= base_url('assets/img/logo-desa.png') ?>" alt="Logo Desa" class="logo-desa"> -->
                  <img id="logo-desa" src="<?= base_url('konohalogo.png') ?>" alt="Logo Desa Konoha">

                  <h3>Sistem Informasi Desa</h3>
                  <p class="text-muted">Silakan masuk dengan akun Anda</p>
               </div>

               <?php if (session()->getFlashdata('error')) : ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <?= session()->getFlashdata('error') ?>
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
               <?php endif; ?>

               <form action="<?= base_url('login') ?>" method="post">
                  <?= csrf_field() ?>

                  <div class="mb-3">
                     <label for="username" class="form-label">Username</label>
                     <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                        id="username" name="username" value="<?= old('username') ?>" required>
                     <?php if (session('errors.username')) : ?>
                        <div class="invalid-feedback">
                           <?= session('errors.username') ?>
                        </div>
                     <?php endif; ?>
                  </div>

                  <div class="mb-3">
                     <label for="password" class="form-label">Password</label>
                     <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                        id="password" name="password" required>
                     <?php if (session('errors.password')) : ?>
                        <div class="invalid-feedback">
                           <?= session('errors.password') ?>
                        </div>
                     <?php endif; ?>
                  </div>

                  <div class="d-grid gap-2">
                     <button type="submit" class="btn btn-primary">Masuk</button>
                  </div>
               </form>

               <!-- <div class="text-center mt-3">
                  <p class="text-muted">© <?= date('Y') ?> Sistem Informasi Desa</p>
               </div> -->
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>