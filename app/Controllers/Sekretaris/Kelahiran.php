<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\KelahiranModel;
use App\Models\PendudukModel;
use App\Models\PerkawinanModel;

class Kelahiran extends BaseController
{
   protected $kelahiranModel;
   protected $pendudukModel;
   protected $perkawinanModel;

   public function __construct()
   {
      $this->kelahiranModel = new KelahiranModel();
      $this->pendudukModel = new PendudukModel();
      $this->perkawinanModel = new PerkawinanModel();
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
         'pasangan' => $this->perkawinanModel->getPasanganAktif(),
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

      $perkawinan_id = $this->request->getPost('perkawinan_id');
      $pasangan = $this->perkawinanModel->getPerkawinanWithDetail($perkawinan_id);

      // simpan data penduduk yg lahir
      $pendudukData = [
         'nik' => $this->request->getPost('nik') ?? '',
         'nama_lengkap' => $this->request->getPost('nama_bayi'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
         'agama' => $this->request->getPost('agama') ?? 'Islam',
         'pendidikan_terakhir' => 'Tidak Bersekolah',
         'status_perkawinan' => 'belum_kawin',
         'pekerjaan' => 'Belum Bekerja',
         'penghasilan' => null,
         'alamat' => $pasangan['alamat_suami'] ?? $pasangan['alamat_istri'] ?? 'Alamat tidak diketahui',
         'rt' => $pasangan['rt_suami'] ?? $pasangan['rt_istri'] ?? '001',
         'rw' => $pasangan['rw_suami'] ?? $pasangan['rw_istri'] ?? '001',
         'dusun' => $pasangan['dusun_suami'] ?? $pasangan['dusun_istri'] ?? 'Dusun Utama',
         'status_hidup' => 1
      ];

      // save & return ID penduduk yg lahir tsb
      $penduduk_id = $this->pendudukModel->insert($pendudukData, true);

      // save data kelahiran dengan penduduk_id, biar sinkron
      $kelahiranData = [
         'penduduk_id' => $penduduk_id,
         'perkawinan_id' => $perkawinan_id,
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'berat_badan' => $this->request->getPost('berat_badan'),
         'panjang_badan' => $this->request->getPost('panjang_badan'),
         'nama_ayah' => $pasangan['nama_suami'],
         'nama_ibu' => $pasangan['nama_istri']
      ];


      log_message('debug', 'Data penduduk yang akan disimpan: ' . print_r($pendudukData, true));
      log_message('debug', 'Data kelahiran yang akan disimpan: ' . print_r($kelahiranData, true));


      log_message('debug', 'ID Penduduk yang baru dibuat: ' . $penduduk_id);

      $this->kelahiranModel->save($kelahiranData);

      return redirect()->to('/sekretaris/kelahiran')
         ->with('success', 'Data kelahiran berhasil ditambahkan');
   }

   public function edit($id)
   {
      $kelahiran = $this->kelahiranModel->getKelahiranWithDetail($id);

      if (!$kelahiran) {
         return redirect()->to('/sekretaris/kelahiran')
            ->with('error', 'Data kelahiran tidak ditemukan');
      }

      $data = [
         'title' => 'Edit Data Kelahiran',
         'kelahiran' => $kelahiran,
         'pasangan' => $this->perkawinanModel->getPasanganAktif(),
         'validation' => \Config\Services::validation()
      ];

      return view('sekretaris/kelahiran/edit', $data);
   }

   public function update($id)
   {
      $db = \Config\Database::connect();
      $db->transStart();

      try {
         $kelahiran = $this->kelahiranModel->find($id);
         if (!$kelahiran) {
            throw new \RuntimeException('Data kelahiran tidak ditemukan');
         }

         if (!$this->validate($this->kelahiranModel->getValidationRules())) {
            throw new \RuntimeException(implode(', ', $this->validator->getErrors()));
         }

         $perkawinan_id = $this->request->getPost('perkawinan_id');
         $pasangan = $this->perkawinanModel->getPerkawinanWithDetail($perkawinan_id);

         // Update data penduduk
         $pendudukData = [
            'id' => $kelahiran['penduduk_id'],
            'nik' => $this->request->getPost('nik'),
            'nama_lengkap' => $this->request->getPost('nama_bayi'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'agama' => $this->request->getPost('agama'),
            'alamat' => $pasangan['alamat_suami'] ?? $pasangan['alamat_istri'] ?? $kelahiran['alamat'],
            'rt' => $pasangan['rt_suami'] ?? $pasangan['rt_istri'] ?? $kelahiran['rt'],
            'rw' => $pasangan['rw_suami'] ?? $pasangan['rw_istri'] ?? $kelahiran['rw'],
            'dusun' => $pasangan['dusun_suami'] ?? $pasangan['dusun_istri'] ?? $kelahiran['dusun']
         ];

         if (!$this->pendudukModel->save($pendudukData)) {
            throw new \RuntimeException('Gagal mengupdate data penduduk');
         }

         // Update data kelahiran
         $kelahiranData = [
            'id' => $id,
            'perkawinan_id' => $perkawinan_id,
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'berat_badan' => $this->request->getPost('berat_badan'),
            'panjang_badan' => $this->request->getPost('panjang_badan'),
            'nama_ayah' => $pasangan['nama_suami'],
            'nama_ibu' => $pasangan['nama_istri']
         ];

         if (!$this->kelahiranModel->save($kelahiranData)) {
            throw new \RuntimeException('Gagal mengupdate data kelahiran');
         }

         $db->transComplete();

         return redirect()->to('/sekretaris/kelahiran')
            ->with('success', 'Data kelahiran berhasil diperbarui');
      } catch (\Exception $e) {
         $db->transRollback();
         return redirect()->back()->withInput()
            ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
      }
   }

   public function hapus($id)
   {
      $kelahiran = $this->kelahiranModel->find($id);

      if ($kelahiran) {
         if ($kelahiran['penduduk_id']) {
            $this->pendudukModel->delete($kelahiran['penduduk_id']);
         }
         $this->kelahiranModel->delete($id);
      }

      return redirect()->to('/sekretaris/kelahiran')
         ->with('success', 'Data kelahiran berhasil dihapus');
   }
}
