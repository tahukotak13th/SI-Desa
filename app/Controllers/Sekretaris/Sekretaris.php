<?php

namespace App\Controllers;

use App\Models\PendudukModel;
use App\Models\PendidikanModel;
use App\Models\KelahiranModel;
use App\Models\KematianModel;
use App\Models\PerkawinanModel;
use App\Models\SuratKeteranganModel;
use App\Models\JenisSuratModel;

class Sekretaris extends BaseController
{
   protected $pendudukModel;
   protected $pendidikanModel;
   protected $kelahiranModel;
   protected $kematianModel;
   protected $perkawinanModel;
   protected $suratModel;
   protected $jenisSuratModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      $this->pendidikanModel = new PendidikanModel();
      $this->kelahiranModel = new KelahiranModel();
      $this->kematianModel = new KematianModel();
      $this->perkawinanModel = new PerkawinanModel();
      $this->suratModel = new SuratKeteranganModel();
      $this->jenisSuratModel = new JenisSuratModel();

      helper('form');
   }

   public function index()
   {
      $data = [
         'title' => 'Dashboard Sekretaris',
         'total_penduduk' => $this->pendudukModel->countAll(),
         'kelahiran_bulan_ini' => $this->kelahiranModel->where('MONTH(tanggal_lahir)', date('m'))->countAllResults(),
         'kematian_bulan_ini' => $this->kematianModel->where('MONTH(tanggal_meninggal)', date('m'))->countAllResults(),
         'surat_pending' => $this->suratModel->where('status', 'diajukan')->countAllResults(),
         'surat_terbaru' => $this->suratModel->getSuratTerbaru(),
         'peristiwa_terbaru' => $this->getPeristiwaTerbaru()
      ];
      return view('sekretaris/dashboard', $data);
   }

   // ==================== DATA PENDUDUK ====================
   public function penduduk()
   {
      $data = [
         'title' => 'Data Penduduk',
         'penduduk' => $this->pendudukModel->findAll()
      ];
      return view('sekretaris/penduduk/index', $data);
   }

   public function tambahPenduduk()
   {
      $data = [
         'title' => 'Tambah Data Penduduk',
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/penduduk/tambah', $data);
   }

   public function simpanPenduduk()
   {
      // Validasi input
      if (!$this->validate([
         'nik' => 'required|is_unique[penduduk.nik]|numeric|min_length[16]|max_length[16]',
         'nama_lengkap' => 'required',
         'tempat_lahir' => 'required',
         'tanggal_lahir' => 'required',
         'jenis_kelamin' => 'required',
         'agama' => 'required',
         'status_perkawinan' => 'required',
         'pekerjaan' => 'required',
         'alamat' => 'required',
         'rt' => 'required',
         'rw' => 'required',
         'dusun' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $this->pendudukModel->save([
         'nik' => $this->request->getVar('nik'),
         'nama_lengkap' => $this->request->getVar('nama_lengkap'),
         'tempat_lahir' => $this->request->getVar('tempat_lahir'),
         'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
         'agama' => $this->request->getVar('agama'),
         'status_perkawinan' => $this->request->getVar('status_perkawinan'),
         'pekerjaan' => $this->request->getVar('pekerjaan'),
         'penghasilan' => $this->request->getVar('penghasilan'),
         'alamat' => $this->request->getVar('alamat'),
         'rt' => $this->request->getVar('rt'),
         'rw' => $this->request->getVar('rw'),
         'dusun' => $this->request->getVar('dusun'),
         'status_hidup' => 1
      ]);

      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil ditambahkan');
   }

   public function editPenduduk($id)
   {
      $data = [
         'title' => 'Edit Data Penduduk',
         'penduduk' => $this->pendudukModel->find($id),
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/penduduk/edit', $data);
   }

   public function updatePenduduk($id)
   {
      // Validasi input
      $rules = [
         'nama_lengkap' => 'required',
         'tempat_lahir' => 'required',
         'tanggal_lahir' => 'required',
         'jenis_kelamin' => 'required',
         'agama' => 'required',
         'status_perkawinan' => 'required',
         'pekerjaan' => 'required',
         'alamat' => 'required',
         'rt' => 'required',
         'rw' => 'required',
         'dusun' => 'required'
      ];

      // Cek jika NIK diubah
      if ($this->request->getVar('nik') != $this->request->getVar('nik_lama')) {
         $rules['nik'] = 'required|is_unique[penduduk.nik]|numeric|min_length[16]|max_length[16]';
      }

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $this->pendudukModel->save([
         'id' => $id,
         'nik' => $this->request->getVar('nik'),
         'nama_lengkap' => $this->request->getVar('nama_lengkap'),
         'tempat_lahir' => $this->request->getVar('tempat_lahir'),
         'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
         'agama' => $this->request->getVar('agama'),
         'status_perkawinan' => $this->request->getVar('status_perkawinan'),
         'pekerjaan' => $this->request->getVar('pekerjaan'),
         'penghasilan' => $this->request->getVar('penghasilan'),
         'alamat' => $this->request->getVar('alamat'),
         'rt' => $this->request->getVar('rt'),
         'rw' => $this->request->getVar('rw'),
         'dusun' => $this->request->getVar('dusun')
      ]);

      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil diupdate');
   }

   public function hapusPenduduk($id)
   {
      $this->pendudukModel->delete($id);
      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil dihapus');
   }

   // ==================== PENDIDIKAN ====================
   public function pendidikan($pendudukId)
   {
      $data = [
         'title' => 'Data Pendidikan',
         'pendidikan' => $this->pendidikanModel->where('penduduk_id', $pendudukId)->findAll(),
         'penduduk' => $this->pendudukModel->find($pendudukId)
      ];
      return view('sekretaris/pendidikan/index', $data);
   }

   public function tambahPendidikan($pendudukId)
   {
      $data = [
         'title' => 'Tambah Data Pendidikan',
         'penduduk' => $this->pendudukModel->find($pendudukId),
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/pendidikan/tambah', $data);
   }

   public function simpanPendidikan($pendudukId)
   {
      if (!$this->validate([
         'tingkat_pendidikan' => 'required',
         'nama_instansi' => 'required',
         'tahun_lulus' => 'required|numeric|min_length[4]|max_length[4]'
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $this->pendidikanModel->save([
         'penduduk_id' => $pendudukId,
         'tingkat_pendidikan' => $this->request->getVar('tingkat_pendidikan'),
         'nama_instansi' => $this->request->getVar('nama_instansi'),
         'tahun_lulus' => $this->request->getVar('tahun_lulus')
      ]);

      return redirect()->to("/sekretaris/pendidikan/$pendudukId")->with('success', 'Data pendidikan berhasil ditambahkan');
   }

   public function hapusPendidikan($pendudukId, $id)
   {
      $this->pendidikanModel->delete($id);
      return redirect()->to("/sekretaris/pendidikan/$pendudukId")->with('success', 'Data pendidikan berhasil dihapus');
   }

   // ==================== KELAHIRAN ====================
   public function kelahiran()
   {
      $data = [
         'title' => 'Data Kelahiran',
         'kelahiran' => $this->kelahiranModel->getKelahiranWithPenduduk()
      ];
      return view('sekretaris/kelahiran/index', $data);
   }

   public function tambahKelahiran()
   {
      $data = [
         'title' => 'Tambah Data Kelahiran',
         'validation' => \Config\Services::validation(),
         'penduduk' => $this->pendudukModel->where('status_hidup', 1)->findAll()
      ];
      return view('sekretaris/kelahiran/tambah', $data);
   }

   public function simpanKelahiran()
   {
      if (!$this->validate([
         'penduduk_id' => 'required',
         'tanggal_lahir' => 'required',
         'tempat_lahir' => 'required',
         'nama_ayah' => 'required',
         'nama_ibu' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $this->kelahiranModel->save([
         'penduduk_id' => $this->request->getVar('penduduk_id'),
         'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
         'tempat_lahir' => $this->request->getVar('tempat_lahir'),
         'berat_badan' => $this->request->getVar('berat_badan'),
         'panjang_badan' => $this->request->getVar('panjang_badan'),
         'nama_ayah' => $this->request->getVar('nama_ayah'),
         'nama_ibu' => $this->request->getVar('nama_ibu')
      ]);

      return redirect()->to('/sekretaris/kelahiran')->with('success', 'Data kelahiran berhasil ditambahkan');
   }

   public function hapusKelahiran($id)
   {
      $this->kelahiranModel->delete($id);
      return redirect()->to('/sekretaris/kelahiran')->with('success', 'Data kelahiran berhasil dihapus');
   }

   // ==================== KEMATIAN ====================
   public function kematian()
   {
      $data = [
         'title' => 'Data Kematian',
         'kematian' => $this->kematianModel->getKematianWithPenduduk()
      ];
      return view('sekretaris/kematian/index', $data);
   }

   public function tambahKematian()
   {
      $data = [
         'title' => 'Tambah Data Kematian',
         'validation' => \Config\Services::validation(),
         'penduduk' => $this->pendudukModel->where('status_hidup', 1)->findAll()
      ];
      return view('sekretaris/kematian/tambah', $data);
   }

   public function simpanKematian()
   {
      if (!$this->validate([
         'penduduk_id' => 'required',
         'tanggal_meninggal' => 'required',
         'penyebab' => 'required',
         'tempat_meninggal' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Update status hidup penduduk
      $this->pendudukModel->save([
         'id' => $this->request->getVar('penduduk_id'),
         'status_hidup' => 0
      ]);

      $this->kematianModel->save([
         'penduduk_id' => $this->request->getVar('penduduk_id'),
         'tanggal_meninggal' => $this->request->getVar('tanggal_meninggal'),
         'penyebab' => $this->request->getVar('penyebab'),
         'tempat_meninggal' => $this->request->getVar('tempat_meninggal')
      ]);

      return redirect()->to('/sekretaris/kematian')->with('success', 'Data kematian berhasil ditambahkan');
   }

   public function hapusKematian($id)
   {
      $kematian = $this->kematianModel->find($id);

      // Kembalikan status hidup penduduk
      $this->pendudukModel->save([
         'id' => $kematian['penduduk_id'],
         'status_hidup' => 1
      ]);

      $this->kematianModel->delete($id);
      return redirect()->to('/sekretaris/kematian')->with('success', 'Data kematian berhasil dihapus');
   }

   // ==================== PERKAWINAN ====================
   public function perkawinan()
   {
      $data = [
         'title' => 'Data Perkawinan',
         'perkawinan' => $this->perkawinanModel->getPerkawinanWithPasangan()
      ];
      return view('sekretaris/perkawinan/index', $data);
   }

   public function tambahPerkawinan()
   {
      $data = [
         'title' => 'Tambah Data Perkawinan',
         'validation' => \Config\Services::validation(),
         'penduduk' => $this->pendudukModel->where('status_hidup', 1)->findAll()
      ];
      return view('sekretaris/perkawinan/tambah', $data);
   }

   public function simpanPerkawinan()
   {
      if (!$this->validate([
         'suami_id' => 'required',
         'istri_id' => 'required',
         'tanggal_perkawinan' => 'required',
         'tempat_perkawinan' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $this->perkawinanModel->save([
         'suami_id' => $this->request->getVar('suami_id'),
         'istri_id' => $this->request->getVar('istri_id'),
         'tanggal_perkawinan' => $this->request->getVar('tanggal_perkawinan'),
         'tempat_perkawinan' => $this->request->getVar('tempat_perkawinan'),
         'status' => 'aktif'
      ]);

      // Update status perkawinan penduduk
      $this->pendudukModel->save([
         'id' => $this->request->getVar('suami_id'),
         'status_perkawinan' => 'kawin'
      ]);

      $this->pendudukModel->save([
         'id' => $this->request->getVar('istri_id'),
         'status_perkawinan' => 'kawin'
      ]);

      return redirect()->to('/sekretaris/perkawinan')->with('success', 'Data perkawinan berhasil ditambahkan');
   }

   public function hapusPerkawinan($id)
   {
      $perkawinan = $this->perkawinanModel->find($id);

      // Kembalikan status perkawinan penduduk
      $this->pendudukModel->save([
         'id' => $perkawinan['suami_id'],
         'status_perkawinan' => 'belum_kawin'
      ]);

      $this->pendudukModel->save([
         'id' => $perkawinan['istri_id'],
         'status_perkawinan' => 'belum_kawin'
      ]);

      $this->perkawinanModel->delete($id);
      return redirect()->to('/sekretaris/perkawinan')->with('success', 'Data perkawinan berhasil dihapus');
   }

   // ==================== SURAT KETERANGAN ====================
   public function surat($jenis = null)
   {
      $data = [
         'title' => 'Surat Keterangan',
         'surat' => $this->suratModel->getAllSurat(),
         'jenisSurat' => $this->jenisSuratModel->findAll()
      ];

      if ($jenis) {
         $data['jenis'] = $this->jenisSuratModel->where('kode_surat', $jenis)->first();
      }

      return view('sekretaris/surat/index', $data);
   }

   public function buatSurat($jenis)
   {
      $jenisSurat = $this->jenisSuratModel->where('kode_surat', $jenis)->first();

      $data = [
         'title' => 'Buat ' . $jenisSurat['nama_surat'],
         'jenisSurat' => $jenisSurat,
         'penduduk' => $this->pendudukModel->findAll(),
         'validation' => \Config\Services::validation()
      ];

      return view('sekretaris/surat/buat', $data);
   }

   public function simpanSurat()
   {
      $jenisSuratId = $this->request->getVar('jenis_surat_id');
      $jenisSurat = $this->jenisSuratModel->find($jenisSuratId);

      if (!$this->validate([
         'penduduk_id' => 'required',
         'isi_surat' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Generate nomor surat
      $noSurat = $this->generateNomorSurat($jenisSurat->kode_surat);

      $data = [
         'no_surat' => $noSurat,
         'jenis_surat_id' => $jenisSuratId,
         'penduduk_id' => $this->request->getVar('penduduk_id'),
         'sekretaris_id' => session('id'),
         'isi_surat' => $this->request->getVar('isi_surat'),
         'status' => 'diajukan',
         'tanggal_pengajuan' => date('Y-m-d')
      ];

      $this->suratModel->save($data);

      return redirect()->to('/sekretaris/surat')->with('success', 'Surat berhasil dibuat dan diajukan');
   }

   public function cetakSurat($id)
   {
      $surat = $this->suratModel->getSuratById($id);

      if ($surat['status'] != 'disetujui') {
         return redirect()->back()->with('error', 'Surat belum disetujui, tidak dapat dicetak');
      }

      $data = [
         'title' => 'Cetak ' . $surat['nama_surat'],
         'surat' => $surat
      ];

      return view('sekretaris/surat/cetak', $data);
   }

   // Helper methods
   private function generateNomorSurat($kodeSurat)
   {
      $prefix = $kodeSurat . '/' . date('Y') . '/';
      $lastSurat = $this->suratModel->like('no_surat', $prefix)->orderBy('no_surat', 'DESC')->first();

      if ($lastSurat) {
         $lastNumber = (int) substr($lastSurat['no_surat'], strlen($prefix));
         $newNumber = $lastNumber + 1;
      } else {
         $newNumber = 1;
      }

      return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
   }

   private function getPeristiwaTerbaru()
   {
      $kelahiran = $this->kelahiranModel->select('penduduk.nama_lengkap as nama, tanggal_lahir as tanggal, "Kelahiran" as jenis')
         ->join('penduduk', 'penduduk.id = kelahiran.penduduk_id')
         ->orderBy('tanggal_lahir', 'DESC')
         ->limit(3)
         ->findAll();

      $kematian = $this->kematianModel->select('penduduk.nama_lengkap as nama, tanggal_meninggal as tanggal, "Kematian" as jenis')
         ->join('penduduk', 'penduduk.id = kematian.penduduk_id')
         ->orderBy('tanggal_meninggal', 'DESC')
         ->limit(3)
         ->findAll();

      $perkawinan = $this->perkawinanModel->select('CONCAT(p1.nama_lengkap, " & ", p2.nama_lengkap) as nama, tanggal_perkawinan as tanggal, "Perkawinan" as jenis')
         ->join('penduduk p1', 'p1.id = perkawinan.suami_id')
         ->join('penduduk p2', 'p2.id = perkawinan.istri_id')
         ->orderBy('tanggal_perkawinan', 'DESC')
         ->limit(3)
         ->findAll();

      $peristiwa = array_merge($kelahiran, $kematian, $perkawinan);
      usort($peristiwa, function ($a, $b) {
         return strtotime($b['tanggal']) - strtotime($a['tanggal']);
      });

      return array_slice($peristiwa, 0, 5);
   }
}
