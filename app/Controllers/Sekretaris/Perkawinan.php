<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\PerkawinanModel;
use App\Models\PendudukModel;

class Perkawinan extends BaseController
{
   protected $perkawinanModel;
   protected $pendudukModel;

   public function __construct()
   {
      $this->perkawinanModel = new PerkawinanModel();
      $this->pendudukModel = new PendudukModel();
      helper('form');
   }

   public function index()
   {
      $data = [
         'title' => 'Data Perkawinan',
         'perkawinan' => $this->perkawinanModel->getPerkawinanWithPasangan()
      ];
      return view('sekretaris/perkawinan/index', $data);
   }

   public function tambah()
   {
      $data = [
         'title' => 'Tambah Data Perkawinan',
         'penduduk_laki' => $this->pendudukModel->where('jenis_kelamin', 'L')->findAll(),
         'penduduk_perempuan' => $this->pendudukModel->where('jenis_kelamin', 'P')->findAll(),
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/perkawinan/tambah', $data);
   }

   public function simpan()
   {
      $suami_id = $this->request->getPost('suami_id');
      $istri_id = $this->request->getPost('istri_id');
      $status = $this->request->getPost('status');

      // Validasi pasangan
      if (!$this->perkawinanModel->validateGenderPair($suami_id, $istri_id)) {
         return redirect()->back()->withInput()
            ->with('error', 'Perkawinan hanya boleh antara laki-laki dan perempuan');
      }

      // Validasi form
      if (!$this->validate($this->perkawinanModel->getValidationRules())) {
         return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
      }

      $data = [
         'suami_id' => $suami_id,
         'istri_id' => $istri_id,
         'tanggal_perkawinan' => $this->request->getPost('tanggal_perkawinan'),
         'tempat_perkawinan' => $this->request->getPost('tempat_perkawinan'),
         'status' => $status
      ];

      $this->perkawinanModel->save($data);

      // Update status penduduk
      $this->perkawinanModel->updateStatusPenduduk($suami_id, $istri_id, $status);

      return redirect()->to('/sekretaris/perkawinan')
         ->with('success', 'Data perkawinan berhasil ditambahkan');
   }

   public function hapus($id)
   {
      $perkawinan = $this->perkawinanModel->find($id);

      if ($perkawinan) {
         // Kembalikan status perkawinan penduduk ke belum kawin
         $this->pendudukModel->update($perkawinan['suami_id'], ['status_perkawinan' => 'belum_kawin']);
         $this->pendudukModel->update($perkawinan['istri_id'], ['status_perkawinan' => 'belum_kawin']);

         $this->perkawinanModel->delete($id);
      }

      return redirect()->to('/sekretaris/perkawinan')
         ->with('success', 'Data perkawinan berhasil dihapus');
   }

   public function edit($id)
   {
      $perkawinan = $this->perkawinanModel->getPerkawinanWithDetail($id);

      if (!$perkawinan) {
         return redirect()->to('/sekretaris/perkawinan')
            ->with('error', 'Data perkawinan tidak ditemukan');
      }

      $data = [
         'title' => 'Edit Data Perkawinan',
         'perkawinan' => $perkawinan,
         'penduduk_laki' => $this->pendudukModel->where('jenis_kelamin', 'L')->findAll(),
         'penduduk_perempuan' => $this->pendudukModel->where('jenis_kelamin', 'P')->findAll(),
         'validation' => \Config\Services::validation()
      ];

      return view('sekretaris/perkawinan/edit', $data);
   }

   public function update($id)
   {
      $perkawinan = $this->perkawinanModel->find($id);
      if (!$perkawinan) {
         return redirect()->to('/sekretaris/perkawinan')
            ->with('error', 'Data perkawinan tidak ditemukan');
      }

      $suami_id = $this->request->getPost('suami_id');
      $istri_id = $this->request->getPost('istri_id');
      $status = $this->request->getPost('status');

      // Validasi pasangan
      if (!$this->perkawinanModel->validateGenderPair($suami_id, $istri_id)) {
         return redirect()->back()->withInput()
            ->with('error', 'Perkawinan hanya boleh antara laki-laki dan perempuan');
      }

      if (!$this->validate($this->perkawinanModel->getValidationRules())) {
         return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
      }

      $this->pendudukModel->update($perkawinan['suami_id'], ['status_perkawinan' => 'belum_kawin']);
      $this->pendudukModel->update($perkawinan['istri_id'], ['status_perkawinan' => 'belum_kawin']);

      $data = [
         'id' => $id,
         'suami_id' => $suami_id,
         'istri_id' => $istri_id,
         'tanggal_perkawinan' => $this->request->getPost('tanggal_perkawinan'),
         'tempat_perkawinan' => $this->request->getPost('tempat_perkawinan'),
         'status' => $status ?? 'Kawin'
      ];

      $this->perkawinanModel->save($data);

      // Update status penduduk baru
      $this->perkawinanModel->updateStatusPenduduk($suami_id, $istri_id, $status);

      return redirect()->to('/sekretaris/perkawinan')
         ->with('success', 'Data perkawinan berhasil diperbarui');
   }
}
