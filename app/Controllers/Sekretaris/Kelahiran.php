<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\KelahiranModel;
use App\Models\PendudukModel;

class Kelahiran extends BaseController
{
   protected $kelahiranModel;
   protected $pendudukModel;

   public function __construct()
   {
      $this->kelahiranModel = new KelahiranModel();
      $this->pendudukModel = new PendudukModel();
      helper('form');
   }

   public function index()
   {
      $data = [
         'title' => 'Data Kelahiran',
         'kelahiran' => $this->kelahiranModel->getKelahiranWithPenduduk()
      ];
      return view('sekretaris/kelahiran/index', $data);
   }

   public function tambah()
   {
      $data = [
         'title' => 'Tambah Data Kelahiran',
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/kelahiran/tambah', $data);
   }

   public function simpan()
   {
      if (!$this->validate($this->kelahiranModel->getValidationRules())) {
         return redirect()->back()->withInput()
            ->with('errors', $this->validator->getErrors());
      }

      // Data penduduk baru
      $pendudukData = [
         'nik' => $this->request->getPost('nik'),
         'nama_lengkap' => $this->request->getPost('nama_bayi'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
         'agama' => $this->request->getPost('agama'),
         'status_perkawinan' => 'belum_kawin',
         'pekerjaan' => 'Belum Bekerja',
         'alamat' => $this->request->getPost('alamat_orangtua'),
         'rt' => $this->request->getPost('rt'),
         'rw' => $this->request->getPost('rw'),
         'dusun' => $this->request->getPost('dusun'),
         'status_hidup' => 1
      ];

      // Buat data penduduk baru
      $penduduk_id = $this->kelahiranModel->createPenduduk($pendudukData);

      // Data kelahiran
      $kelahiranData = [
         'penduduk_id' => $penduduk_id,
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'berat_badan' => $this->request->getPost('berat_badan'),
         'panjang_badan' => $this->request->getPost('panjang_badan'),
         'nama_ayah' => $this->request->getPost('nama_ayah'),
         'nama_ibu' => $this->request->getPost('nama_ibu')
      ];

      $this->kelahiranModel->save($kelahiranData);

      return redirect()->to('/sekretaris/kelahiran')
         ->with('success', 'Data kelahiran berhasil ditambahkan');
   }

   public function hapus($id)
   {
      $kelahiran = $this->kelahiranModel->find($id);

      if ($kelahiran) {
         // Hapus data penduduk terkait jika ada
         if ($kelahiran['penduduk_id']) {
            $this->pendudukModel->delete($kelahiran['penduduk_id']);
         }

         $this->kelahiranModel->delete($id);
      }

      return redirect()->to('/sekretaris/kelahiran')
         ->with('success', 'Data kelahiran berhasil dihapus');
   }
}
