<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PejabatModel;
use App\Models\UserModel;

class PejabatController extends BaseController
{
   protected $pejabatModel;
   protected $userModel;

   public function __construct()
   {
      $this->pejabatModel = new PejabatModel();
      $this->userModel = new UserModel();
   }

   // ke dashboard
   public function managePejabat()
   {
      $data = [
         'title' => 'Manajemen Pejabat',
         'pejabat' => $this->pejabatModel->findAll()
      ];

      return view('admin/pejabat/manage_pejabat', $data);
   }

   // tambah pejabat
   public function create()
   {
      $data = [
         'title' => 'Tambah Pejabat Baru',
         'users' => $this->userModel->findAll(),
         'validation' => \Config\Services::validation()
      ];

      return view('admin/pejabat/create', $data);
   }

   // save data pejabat baru
   public function store()
   {
      $rules = [
         'nama_lengkap' => 'required|min_length[3]',
         'jabatan' => 'required|min_length[3]',
         'periode_mulai' => 'required|valid_date',
         'periode_selesai' => 'permit_empty|valid_date'
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'jabatan' => $this->request->getPost('jabatan'),
         'periode_mulai' => $this->request->getPost('periode_mulai'),
         'periode_selesai' => $this->request->getPost('periode_selesai'),
         'keterangan' => $this->request->getPost('keterangan'),
         'user_id' => $this->request->getPost('user_id')
      ];

      $this->pejabatModel->save($data);
      return redirect()->to('/admin/pejabat')->with('message', 'Pejabat berhasil ditambahkan');
   }

   // edit pejabat
   public function edit($id)
   {
      $pejabatModel = new PejabatModel();

      $data = [
         'title' => 'Edit Pejabat Desa',
         'pejabat' => $pejabatModel->find($id),
         'validation' => \Config\Services::validation()
      ];

      return view('admin/pejabat/edit', $data);
   }

   public function update($id)
   {
      $rules = [
         'nama_lengkap' => 'required|min_length[3]',
         'jabatan' => 'required|min_length[3]',
         'periode_mulai' => 'required|valid_date',
         'periode_selesai' => 'permit_empty|valid_date'
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'id' => $id,
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'jabatan' => $this->request->getPost('jabatan'),
         'periode_mulai' => $this->request->getPost('periode_mulai'),
         'periode_selesai' => $this->request->getPost('periode_selesai'),
         'keterangan' => $this->request->getPost('keterangan')
      ];

      $pejabatModel = new PejabatModel();
      $pejabatModel->save($data);

      return redirect()->to('/admin/pejabat')->with('message', 'Data pejabat berhasil diupdate');
   }

   // Hapus pejabat
   public function delete($id)
   {
      $this->pejabatModel->delete($id);
      return redirect()->to('/admin/pejabat')->with('message', 'Pejabat berhasil dihapus');
   }
}
