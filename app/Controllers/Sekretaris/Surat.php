<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\PendudukModel;
use App\Models\SuratKeteranganModel;
use App\Models\JenisSuratModel;

class Surat extends BaseController
{
   protected $pendudukModel;
   protected $suratModel;
   protected $jenisSuratModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      $this->suratModel = new SuratKeteranganModel();
      $this->jenisSuratModel = new JenisSuratModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Buat Surat Keterangan',
         'jenis_surat' => $this->jenisSuratModel->findAll()
      ];
      return view('sekretaris/surat/index', $data);
   }

   public function jenis($kode)
   {
      $jenisSurat = $this->jenisSuratModel->where('kode_surat', $kode)->first();

      if (!$jenisSurat) {
         return redirect()->back()->with('error', 'Jenis surat tidak ditemukan');
      }

      $data = [
         'title' => 'Buat ' . $jenisSurat['nama_surat'],
         'jenis_surat' => $jenisSurat,
         'penduduk' => $this->pendudukModel->findAll()
      ];

      return view('sekretaris/surat/pilih_penduduk', $data);
   }

   public function buat()
   {
      $jenisKode = $this->request->getGet('jenis');
      $pendudukId = $this->request->getGet('penduduk_id');

      // Validasi
      if (empty($jenisKode) || empty($pendudukId)) {
         return redirect()->to('/sekretaris/surat')
            ->with('error', 'Parameter tidak lengkap');
      }

      $penduduk = $this->pendudukModel->find($pendudukId);
      $jenisSurat = $this->jenisSuratModel->where('kode_surat', $jenisKode)->first();

      if (!$penduduk || !$jenisSurat) {
         return redirect()->to('/sekretaris/surat')
            ->with('error', 'Data tidak ditemukan');
      }

      // Template berbeda berdasarkan jenis surat
      $template = $this->getTemplateByJenis($jenisKode, $penduduk);

      $data = [
         'title' => 'Buat ' . $jenisSurat['nama_surat'],
         'penduduk' => $penduduk,
         'jenis_surat' => $jenisSurat,
         'no_surat' => $this->generateNomorSurat($jenisKode),
         'isi_surat' => $template,
         'tanggal' => date('d F Y')
      ];

      return view('sekretaris/surat/form_surat', $data);
   }

   private function getTemplateByJenis($kode, $penduduk)
   {
      $templates = [
         'SK-DOM' => "Yang bertanda tangan di bawah ini menerangkan bahwa:\n\n"
            . "Nama        : {{nama}}\n"
            . "NIK         : {{nik}}\n"
            . "TTL         : {{tempat_lahir}}, {{tanggal_lahir}}\n"
            . "Alamat      : {{alamat}}, RT {{rt}} RW {{rw}}, Dusun {{dusun}}\n\n"
            . "Adalah benar-benar penduduk yang berdomisili di wilayah Desa ini.",

         'SK-TM' => "Yang bertanda tangan di bawah ini menerangkan bahwa:\n\n"
            . "Nama        : {{nama}}\n"
            . "NIK         : {{nik}}\n"
            . "Alamat      : {{alamat}}\n\n"
            . "Adalah warga tidak mampu yang membutuhkan bantuan.",

         // Tambahkan template untuk jenis surat lainnya
      ];

      $template = $templates[$kode] ?? $templates['SK-DOM']; // Default ke SK-DOM jika tidak ditemukan

      return str_replace(
         ['{{nama}}', '{{nik}}', '{{tempat_lahir}}', '{{tanggal_lahir}}', '{{alamat}}', '{{rt}}', '{{rw}}', '{{dusun}}'],
         [
            $penduduk['nama_lengkap'],
            $penduduk['nik'],
            $penduduk['tempat_lahir'],
            date('d-m-Y', strtotime($penduduk['tanggal_lahir'])),
            $penduduk['alamat'],
            $penduduk['rt'],
            $penduduk['rw'],
            $penduduk['dusun']
         ],
         $template
      );
   }

   public function pilihPenduduk()
   {
      $jenisKode = $this->request->getGet('jenis');
      $jenisSurat = $this->jenisSuratModel->where('kode_surat', $jenisKode)->first();

      if (!$jenisSurat) {
         return redirect()->to('/sekretaris/surat')->with('error', 'Jenis surat tidak valid');
      }

      $data = [
         'title' => 'Pilih Penduduk untuk ' . $jenisSurat['nama_surat'],
         'jenis_surat' => $jenisSurat,
         'penduduk' => $this->pendudukModel->findAll()
      ];

      return view('sekretaris/surat/pilih_penduduk', $data);
   }

   public function simpan()
   {
      $rules = [
         'jenis_surat_id' => 'required',
         'penduduk_id' => 'required',
         'no_surat' => 'required',
         'isi_surat' => 'required'
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'no_surat' => $this->request->getPost('no_surat'),
         'jenis_surat_id' => $this->request->getPost('jenis_surat_id'),
         'penduduk_id' => $this->request->getPost('penduduk_id'),
         'sekretaris_id' => session('id'),
         'isi_surat' => $this->request->getPost('isi_surat'),
         'status' => 'disetujui',
         'tanggal_pengajuan' => date('Y-m-d')
      ];

      $this->suratModel->save($data);

      return redirect()->to('/sekretaris/surat/cetak/' . $this->suratModel->getInsertID())
         ->with('success', 'Surat berhasil dibuat');
   }

   public function cetak($id)
   {
      $surat = $this->suratModel->getSuratWithDetail($id);

      if (!$surat) {
         return redirect()->back()->with('error', 'Surat tidak ditemukan');
      }

      $data = [
         'title' => 'Cetak ' . $surat['nama_surat'],
         'surat' => $surat,
         'tanggal_cetak' => date('d F Y')
      ];

      return view('sekretaris/surat/cetak', $data);
   }

   protected function generateNomorSurat($kode)
   {
      $month = date('m');
      $year = date('Y');

      $lastSurat = $this->suratModel
         ->where('YEAR(tanggal_pengajuan)', $year)
         ->where('MONTH(tanggal_pengajuan)', $month)
         ->countAllResults();

      $seq = str_pad($lastSurat + 1, 3, '0', STR_PAD_LEFT);

      return "$kode/$month/$year/$seq";
   }
}
