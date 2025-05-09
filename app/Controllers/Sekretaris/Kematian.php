<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\KematianModel;
use App\Models\PendudukModel;

class Kematian extends BaseController
{
   protected $kematianModel;
   protected $pendudukModel;

   public function __construct()
   {
      $this->kematianModel = new KematianModel();
      $this->pendudukModel = new PendudukModel();
      helper('form');
   }

   public function index()
   {
      $data = [
         'title' => 'Data Kematian',
         'kematian' => $this->kematianModel->getKematianWithPenduduk()
      ];
      return view('sekretaris/kematian/index', $data);
   }

   public function tambah()
   {
      $data = [
         'title' => 'Tambah Data Kematian',
         'penduduk' => $this->pendudukModel->where('status_hidup', 1)->findAll(),
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/kematian/tambah', $data);
   }

   public function simpan()
   {
      if (!$this->validate($this->kematianModel->getValidationRules())) {
         return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
      }

      $data = [
         'penduduk_id' => $this->request->getPost('penduduk_id'),
         'tanggal_meninggal' => $this->request->getPost('tanggal_meninggal'),
         'penyebab' => $this->request->getPost('penyebab'),
         'tempat_meninggal' => $this->request->getPost('tempat_meninggal')
      ];

      $this->kematianModel->save($data);

      // Update status penduduk
      $this->kematianModel->updateStatusPenduduk($this->request->getPost('penduduk_id'));

      return redirect()->to('/sekretaris/kematian')
         ->with('success', 'Data kematian berhasil ditambahkan');
   }

   public function hapus($id)
   {
      $kematian = $this->kematianModel->find($id);

      if ($kematian) {
         // Kembalikan status penduduk
         $this->pendudukModel->update($kematian['penduduk_id'], ['status_hidup' => 1]);

         // Cek apakah perlu mengembalikan status perkawinan
         $penduduk = $this->pendudukModel->find($kematian['penduduk_id']);
         $perkawinanModel = new \App\Models\PerkawinanModel();

         // Cari data perkawinan dengan status 'Meninggal' yang melibatkan penduduk ini
         $perkawinan = $perkawinanModel->where('status', 'Meninggal')
            ->groupStart()
            ->where('suami_id', $kematian['penduduk_id'])
            ->orWhere('istri_id', $kematian['penduduk_id'])
            ->groupEnd()
            ->first();

         if ($perkawinan) {
            // Kembalikan status perkawinan ke 'Kawin'
            $perkawinanModel->update($perkawinan['id'], ['status' => 'Kawin']);

            // Kembalikan status perkawinan kedua pasangan
            $this->pendudukModel->update($perkawinan['suami_id'], ['status_perkawinan' => 'kawin']);
            $this->pendudukModel->update($perkawinan['istri_id'], ['status_perkawinan' => 'kawin']);
         }

         $this->kematianModel->delete($id);
      }

      return redirect()->to('/sekretaris/kematian')
         ->with('success', 'Data kematian berhasil dihapus');
   }

   public function edit($id)
   {
      $kematian = $this->kematianModel->getKematianWithPendudukById($id);

      if (!$kematian) {
         return redirect()->to('/sekretaris/kematian')
            ->with('error', 'Data kematian tidak ditemukan');
      }

      $data = [
         'title' => 'Edit Data Kematian',
         'kematian' => $kematian,
         'penduduk' => $this->pendudukModel->where('status_hidup', 1)->orWhere('id', $kematian['penduduk_id'])->findAll(),
         'validation' => \Config\Services::validation()
      ];

      return view('sekretaris/kematian/edit', $data);
   }

   public function update($id)
   {
      $kematian = $this->kematianModel->find($id);

      if (!$kematian) {
         return redirect()->to('/sekretaris/kematian')
            ->with('error', 'Data kematian tidak ditemukan');
      }

      // Validasi
      if (!$this->validate($this->kematianModel->getValidationRules())) {
         return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
      }

      $penduduk_id = $this->request->getPost('penduduk_id');

      // Jika penduduk diubah, kembalikan status penduduk lama
      if ($kematian['penduduk_id'] != $penduduk_id) {
         $this->pendudukModel->update($kematian['penduduk_id'], [
            'status_hidup' => 1,
            'status_perkawinan' => $this->pendudukModel->find($kematian['penduduk_id'])->status_perkawinan
         ]);
      }

      $data = [
         'id' => $id,
         'penduduk_id' => $penduduk_id,
         'tanggal_meninggal' => $this->request->getPost('tanggal_meninggal'),
         'penyebab' => $this->request->getPost('penyebab'),
         'tempat_meninggal' => $this->request->getPost('tempat_meninggal')
      ];

      $this->kematianModel->save($data);

      // Update status penduduk baru
      $this->kematianModel->updateStatusPenduduk($penduduk_id);

      return redirect()->to('/sekretaris/kematian')
         ->with('success', 'Data kematian berhasil diperbarui');
   }
}
