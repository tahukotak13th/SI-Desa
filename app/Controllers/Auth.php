<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = new UserModel();
   }

   public function login()
   {
      // Jika sudah login, redirect ke dashboard sesuai level
      if (session()->get('isLoggedIn')) {
         return $this->redirectToDashboard();
      }

      $data = [
         'title' => 'Login | Sistem Informasi Desa',
         'validation' => \Config\Services::validation()
      ];

      return view('auth/login', $data);
   }

   public function attemptLogin()
   {
      // Validasi input
      if (!$this->validate([
         'username' => 'required',
         'password' => 'required'
      ])) {
         return redirect()->back()->withInput();
      }

      $username = $this->request->getVar('username');
      $password = $this->request->getVar('password');

      // Cari user berdasarkan username
      $user = $this->userModel->where('username', $username)->first();

      if (!$user) {
         session()->setFlashdata('error', 'Username tidak ditemukan');
         return redirect()->back()->withInput();
      }

      // Verifikasi password (dua metode: plaintext dan hashed)
      if (!$this->verifyPassword($password, $user['password'])) {
         session()->setFlashdata('error', 'Password salah');
         return redirect()->back()->withInput();
      }

      // Cek status aktif
      if (!$user['is_active']) {
         session()->setFlashdata('error', 'Akun tidak aktif');
         return redirect()->back()->withInput();
      }

      // Set session
      $sessionData = [
         'id' => $user['id'],
         'username' => $user['username'],
         'nama_lengkap' => $user['nama_lengkap'],
         'email' => $user['email'],
         'level' => $user['level'],
         'isLoggedIn' => true
      ];

      session()->set($sessionData);

      return $this->redirectToDashboard();
   }

   /**
    * Verifikasi password dengan dua metode:
    * 1. Langsung compare plaintext (untuk migrasi dari sistem lama)
    * 2. Verifikasi hash (untuk password baru)
    */
   protected function verifyPassword($inputPassword, $databasePassword)
   {
      // Jika password di database adalah hash (diawali dengan $2y$)
      if (strpos($databasePassword, '$2y$') === 0) {
         return password_verify($inputPassword, $databasePassword);
      }

      // Jika password di database adalah plaintext
      return $inputPassword === $databasePassword;
   }

   public function logout()
   {
      session()->destroy();
      return redirect()->to('/login');
   }

   private function redirectToDashboard()
   {
      switch (session()->get('level')) {
         case 'admin':
            return redirect()->to('/admin/dashboard');
         case 'sekretaris':
            return redirect()->to('/sekretaris/dashboard');
         case 'kepala_desa':
            return redirect()->to('/kepala-desa/dashboard');
         default:
            return redirect()->to('/');
      }
   }
}
