<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\PendudukModel;
use App\Models\SuratKeteranganModel;
use App\Models\JenisSuratModel;
use App\Models\KematianModel;

class Surat extends BaseController
{
   protected $pendudukModel;
   protected $suratModel;
   protected $jenisSuratModel;
   protected $kematianModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      $this->suratModel = new SuratKeteranganModel();
      $this->jenisSuratModel = new JenisSuratModel();
      $this->kematianModel = new KematianModel();
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

   // Di Controller Surat.php

   public function buat()
   {
      $jenisKode = $this->request->getGet('jenis');
      $pendudukId = $this->request->getGet('penduduk_id');

      // Validasi
      if (empty($jenisKode) || empty($pendudukId)) {
         return redirect()->to('/sekretaris/surat')
            ->with('error', 'Parameter tidak lengkap');
      }

      // Ambil data penduduk sebagai array
      $penduduk = $this->pendudukModel->find($pendudukId);
      if (!$penduduk) {
         return redirect()->to('/sekretaris/surat')
            ->with('error', 'Data penduduk tidak ditemukan');
      }

      // Konversi ke array jika berupa object
      $pendudukArray = is_object($penduduk) ? $penduduk->toArray() : $penduduk;

      // Ambil data tambahan khusus kematian jika ada
      $dataKematian = [];
      $kematianData = $this->kematianModel->where('penduduk_id', $pendudukId)->first();
      $dataKematian = is_object($kematianData) ? $kematianData->toArray() : ($kematianData ?? []);


      // Gabungkan data
      $mergedData = array_merge($pendudukArray, $dataKematian);

      // Dapatkan template dan data yang dibutuhkan
      $jenisSurat = $this->jenisSuratModel->where('kode_surat', $jenisKode)->first();
      if (!$jenisSurat) {
         return redirect()->to('/sekretaris/surat')
            ->with('error', 'Jenis surat tidak valid');
      }

      $templateData = $this->jenisSuratModel->getTemplateByKode($jenisKode);
      $isiSurat = $this->generateIsiSurat($templateData['template'], $mergedData);

      $data = [
         'title' => 'Buat ' . $jenisSurat['nama_surat'],
         'penduduk' => $mergedData,
         'jenis_surat' => $jenisSurat,
         'no_surat' => $this->generateNomorSurat($jenisKode),
         'isi_surat' => $isiSurat,
         'tanggal' => date('d F Y'),
         'kebutuhan_data' => $templateData['kebutuhan_data'] ?? []
      ];

      return view('sekretaris/surat/form_surat', $data);
   }

   private function generateIsiSurat($template, $data)
   {
      // Format tanggal Indonesia
      $bulanIndo = [
         '01' => 'Januari',
         '02' => 'Februari',
         '03' => 'Maret',
         '04' => 'April',
         '05' => 'Mei',
         '06' => 'Juni',
         '07' => 'Juli',
         '08' => 'Agustus',
         '09' => 'September',
         '10' => 'Oktober',
         '11' => 'November',
         '12' => 'Desember'
      ];

      // Default replacements untuk semua surat
      $replacements = [
         // Data dasar penduduk
         '{{nama}}' => $data['nama_lengkap'] ?? '[Nama tidak tersedia]',
         '{{nik}}' => $data['nik'] ?? '[NIK tidak tersedia]',
         '{{tempat_lahir}}' => $data['tempat_lahir'] ?? '[Tempat lahir tidak tersedia]',
         '{{tanggal_lahir}}' => isset($data['tanggal_lahir']) ?
            date('d-m-Y', strtotime($data['tanggal_lahir'])) : '[Tanggal lahir tidak tersedia]',
         '{{alamat}}' => $data['alamat'] ?? '[Alamat tidak tersedia]',
         '{{rt}}' => $data['rt'] ?? '-',
         '{{rw}}' => $data['rw'] ?? '-',
         '{{dusun}}' => $data['dusun'] ?? '-',
         '{{pekerjaan}}' => $data['pekerjaan'] ?? '-',

         // Data tambahan umum
         '{{penghasilan}}' => isset($data['penghasilan']) ? number_format($data['penghasilan'], 0, ',', '.') : '0',
         '{{terbilang}}' => isset($data['penghasilan']) ? $this->terbilang($data['penghasilan']) : 'nol rupiah',
         '{{nama_desa}}' => 'Konoha',
         '{{kecamatan}}' => 'Konoha',
         '{{kabupaten}}' => 'Konoha',
         '{{tanggal_surat}}' => date('d') . ' ' . $bulanIndo[date('m')] . ' ' . date('Y'),

         // Data khusus kematian
         '{{tanggal_meninggal}}' => isset($data['tanggal_meninggal']) ?
            date('d-m-Y', strtotime($data['tanggal_meninggal'])) : '[Tanggal tidak tersedia]',
         '{{penyebab}}' => $data['penyebab'] ?? '[Penyebab tidak tersedia]',
         '{{tempat_meninggal}}' => $data['tempat_meninggal'] ?? '[Tempat tidak tersedia]',
         '{{nama_alm}}' => 'Alm. ' . ($data['nama_lengkap'] ?? '[Nama tidak tersedia]'),

      ];

      return str_replace(array_keys($replacements), array_values($replacements), $template);
   }

   private function terbilang($angka, $isFinal = true)
   {
      $angka = abs((int)$angka);
      $bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
      $temp = '';

      if ($angka < 12) {
         $temp = $bilangan[$angka];
      } else if ($angka < 20) {
         $temp = $this->terbilang($angka - 10, false) . ' belas';
      } else if ($angka < 100) {
         $temp = $this->terbilang(floor($angka / 10), false) . ' puluh';
         if ($angka % 10 != 0) {
            $temp .= ' ' . $this->terbilang($angka % 10, false);
         }
      } else if ($angka < 200) {
         $temp = 'seratus';
         if ($angka - 100 != 0) {
            $temp .= ' ' . $this->terbilang($angka - 100, false);
         }
      } else if ($angka < 1000) {
         $temp = $this->terbilang(floor($angka / 100), false) . ' ratus';
         if ($angka % 100 != 0) {
            $temp .= ' ' . $this->terbilang($angka % 100, false);
         }
      } else if ($angka < 2000) {
         $temp = 'seribu';
         if ($angka - 1000 != 0) {
            $temp .= ' ' . $this->terbilang($angka - 1000, false);
         }
      } else if ($angka < 1000000) {
         $temp = $this->terbilang(floor($angka / 1000), false) . ' ribu';
         if ($angka % 1000 != 0) {
            $temp .= ' ' . $this->terbilang($angka % 1000, false);
         }
      } else if ($angka < 1000000000) {
         $temp = $this->terbilang(floor($angka / 1000000), false) . ' juta';
         if ($angka % 1000000 != 0) {
            $temp .= ' ' . $this->terbilang($angka % 1000000, false);
         }
      } else {
         $temp = $this->terbilang(floor($angka / 1000000000), false) . ' miliar';
         if ($angka % 1000000000 != 0) {
            $temp .= ' ' . $this->terbilang($angka % 1000000000, false);
         }
      }

      return $isFinal ? trim($temp) . ' rupiah' : trim($temp);
   }


   public function pilihPenduduk()
   {
      $jenisKode = $this->request->getGet('jenis');
      $jenisSurat = $this->jenisSuratModel->where('kode_surat', $jenisKode)->first();

      if (!$jenisSurat) {
         return redirect()->to('/sekretaris/surat')->with('error', 'Jenis surat tidak valid');
      }

      // Query berbeda untuk SK-MATI
      if ($jenisKode == 'SK-MATI') {
         $penduduk = $this->pendudukModel
            ->select('penduduk.*, kematian.tanggal_meninggal')
            ->join('kematian', 'kematian.penduduk_id = penduduk.id')
            ->where('penduduk.status_hidup', 0) // Hanya yang statusnya meninggal
            ->findAll();
      } else {
         $penduduk = $this->pendudukModel->findAll();
      }

      $data = [
         'title' => 'Pilih Penduduk untuk ' . $jenisSurat['nama_surat'],
         'jenis_surat' => $jenisSurat,
         'penduduk' => $penduduk,
         'is_surat_kematian' => ($jenisKode == 'SK-MATI')
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
         'status' => 'diajukan',
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
