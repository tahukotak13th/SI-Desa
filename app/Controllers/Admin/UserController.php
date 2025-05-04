<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = new UserModel();
   }

   // Tampilkan semua user
   public function manageUsers()
   {
      $data = [
         'title' => 'Manajemen User',
         'users' => $this->userModel->findAll()
      ];

      return view('admin/users/manage_users', $data);
      // yg di sini harus sesuai direktori
   }

   // Form tambah user
   public function create()
   {
      $data = [
         'title' => 'Tambah User Baru',
         'validation' => \Config\Services::validation()
      ];

      return view('admin/users/create_user', $data);
   }

   // Simpan user baru
   public function store()
   {
      $rules = [
         'username' => 'required|is_unique[users.username]|min_length[3]',
         'password' => 'required|min_length[6]',
         'nama_lengkap' => 'required',
         'email' => 'required|valid_email|is_unique[users.email]',
         'level' => 'required|in_list[admin,sekretaris,kepala_desa]'
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'username' => $this->request->getPost('username'),
         'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'email' => $this->request->getPost('email'),
         'level' => $this->request->getPost('level'),
         'is_active' => 1
      ];

      // Simpan user baru
      $this->userModel->save($data);
      $userId = $this->userModel->getInsertID();

      // Tambahkan sebagai pejabat sesuai level
      $this->tambahkanSebagaiPejabat($userId, $data['level'], $data['nama_lengkap']);

      return redirect()->to('/admin/users')->with('message', 'User berhasil ditambahkan dan terdaftar sebagai pejabat');
   }

   private function tambahkanSebagaiPejabat($userId, $level, $namaLengkap)
   {
      $pejabatModel = new \App\Models\PejabatModel();

      // Mapping level ke jabatan
      $jabatanMap = [
         'admin' => 'Administrator Sistem',
         'sekretaris' => 'Sekretaris Desa',
         'kepala_desa' => 'Kepala Desa'
      ];

      $dataPejabat = [
         'user_id' => $userId,
         'nama_lengkap' => $namaLengkap,
         'jabatan' => $jabatanMap[$level] ?? 'Staf Desa',
         'periode_mulai' => date('Y-m-d'),
         'keterangan' => 'Otomatis terdaftar saat pembuatan user'
      ];

      $pejabatModel->save($dataPejabat);
   }

   // Form edit user
   public function edit($id)
   {
      $pejabatModel = new \App\Models\PejabatModel();

      $data = [
         'title' => 'Edit User',
         'user' => $this->userModel->find($id),
         'pejabat' => $pejabatModel->where('user_id', $id)->first(),
         'validation' => \Config\Services::validation()
      ];

      return view('admin/users/edit_user', $data);
   }

   public function update($id)
   {
      $user = $this->userModel->find($id);

      $rules = [
         'username' => "required|is_unique[users.username,id,$id]|min_length[3]",
         'nama_lengkap' => 'required',
         'email' => "required|valid_email|is_unique[users.email,id,$id]",
         'level' => 'required|in_list[admin,sekretaris,kepala_desa]'
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'id' => $id,
         'username' => $this->request->getPost('username'),
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'email' => $this->request->getPost('email'),
         'level' => $this->request->getPost('level'),
         'is_active' => $this->request->getPost('is_active') ? 1 : 0
      ];

      // Update password jika diisi
      if ($this->request->getPost('password')) {
         $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
      }

      // Update data user
      $this->userModel->save($data);

      // Update data pejabat terkait
      $this->updatePejabatTerikat($id, $data['level'], $data['nama_lengkap']);

      return redirect()->to('/admin/users')->with('message', 'User berhasil diupdate');
   }

   private function updatePejabatTerikat($userId, $level, $namaLengkap)
   {
      $pejabatModel = new \App\Models\PejabatModel();
      $pejabat = $pejabatModel->where('user_id', $userId)->first();

      if ($pejabat) {
         $jabatanMap = [
            'admin' => 'Administrator Sistem',
            'sekretaris' => 'Sekretaris Desa',
            'kepala_desa' => 'Kepala Desa'
         ];

         $dataPejabat = [
            'id' => $pejabat['id'],
            'nama_lengkap' => $namaLengkap,
            'jabatan' => $jabatanMap[$level] ?? 'Staf Desa'
         ];

         $pejabatModel->save($dataPejabat);
      }
   }

   // Hapus user
   public function delete($id)
   {
      $this->userModel->delete($id);
      return redirect()->to('/admin/users/')->with('message', 'User berhasil dihapus');
   }
}
