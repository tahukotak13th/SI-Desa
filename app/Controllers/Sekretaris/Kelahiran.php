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

      // 1. Simpan data penduduk baru (bayi) terlebih dahulu
      $pendudukData = [
         'nik' => $this->request->getPost('nik') ?? '',
         'nama_lengkap' => $this->request->getPost('nama_bayi'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
         'agama' => $this->request->getPost('agama') ?? 'Islam', // Default value
         'pendidikan_terakhir' => 'Tidak Bersekolah', // Default untuk bayi
         'status_perkawinan' => 'belum_kawin',
         'pekerjaan' => 'Belum Bekerja',
         'penghasilan' => null,
         'alamat' => $pasangan['alamat_suami'] ?? $pasangan['alamat_istri'] ?? 'Alamat tidak diketahui',
         'rt' => $pasangan['rt_suami'] ?? $pasangan['rt_istri'] ?? '001',
         'rw' => $pasangan['rw_suami'] ?? $pasangan['rw_istri'] ?? '001',
         'dusun' => $pasangan['dusun_suami'] ?? $pasangan['dusun_istri'] ?? 'Dusun Utama',
         'status_hidup' => 1
      ];

      // Simpan dan dapatkan ID penduduk baru
      $penduduk_id = $this->pendudukModel->insert($pendudukData, true); // Parameter true untuk return ID

      // 2. Sekarang simpan data kelahiran dengan penduduk_id yang valid
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

      // Di dalam method simpan(), sebelum save:
      log_message('debug', 'Data penduduk yang akan disimpan: ' . print_r($pendudukData, true));
      log_message('debug', 'Data kelahiran yang akan disimpan: ' . print_r($kelahiranData, true));

      // Setelah save:
      log_message('debug', 'ID Penduduk yang baru dibuat: ' . $penduduk_id);

      $this->kelahiranModel->save($kelahiranData);

      return redirect()->to('/sekretaris/kelahiran')
         ->with('success', 'Data kelahiran berhasil ditambahkan');
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
