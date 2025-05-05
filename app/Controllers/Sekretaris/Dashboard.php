<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\PendudukModel;
use App\Models\SuratKeteranganModel;
use App\Models\KelahiranModel;
use App\Models\KematianModel;
use App\Models\PerkawinanModel;

class Dashboard extends BaseController
{
   protected $pendudukModel;
   protected $suratModel;
   protected $kelahiranModel;
   protected $kematianModel;
   protected $perkawinanModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      $this->suratModel = new SuratKeteranganModel();
      $this->kelahiranModel = new KelahiranModel();
      $this->kematianModel = new KematianModel();
      $this->perkawinanModel = new PerkawinanModel();
   }

   public function index()
   {
      $currentMonth = date('m');
      $currentYear = date('Y');

      $data = [
         'title' => 'Dashboard Sekretaris',
         'total_penduduk' => $this->pendudukModel->countAll(),
         'kelahiran_bulan_ini' => $this->kelahiranModel
            ->where('MONTH(kelahiran.tanggal_lahir)', $currentMonth)
            ->where('YEAR(kelahiran.tanggal_lahir)', $currentYear)
            ->countAllResults(),
         'kematian_bulan_ini' => $this->kematianModel
            ->where('MONTH(kematian.tanggal_meninggal)', $currentMonth)
            ->where('YEAR(kematian.tanggal_meninggal)', $currentYear)
            ->countAllResults(),
         'surat_pending' => $this->suratModel
            ->where('status', 'diajukan')
            ->countAllResults(),
         'surat_terbaru' => $this->suratModel
            ->select('surat_keterangan.*, jenis_surat.nama_surat, penduduk.nama_lengkap as nama_penduduk')
            ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
            ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
            ->orderBy('surat_keterangan.created_at', 'DESC')
            ->limit(5)
            ->findAll(),
         'peristiwa_terbaru' => $this->getPeristiwaTerbaru()
      ];

      return view('sekretaris/dashboard', $data);
   }

   private function getPeristiwaTerbaru()
   {
      $kelahiran = $this->kelahiranModel
         ->select('penduduk.nama_lengkap as nama, kelahiran.tanggal_lahir as tanggal, "Kelahiran" as jenis')
         ->join('penduduk', 'penduduk.id = kelahiran.penduduk_id')
         ->orderBy('kelahiran.tanggal_lahir', 'DESC')
         ->limit(3)
         ->findAll();

      $kematian = $this->kematianModel
         ->select('penduduk.nama_lengkap as nama, kematian.tanggal_meninggal as tanggal, "Kematian" as jenis')
         ->join('penduduk', 'penduduk.id = kematian.penduduk_id')
         ->orderBy('kematian.tanggal_meninggal', 'DESC')
         ->limit(3)
         ->findAll();

      $perkawinan = $this->perkawinanModel
         ->select('CONCAT(p1.nama_lengkap, " & ", p2.nama_lengkap) as nama, perkawinan.tanggal_perkawinan as tanggal, "Perkawinan" as jenis')
         ->join('penduduk p1', 'p1.id = perkawinan.suami_id')
         ->join('penduduk p2', 'p2.id = perkawinan.istri_id')
         ->orderBy('perkawinan.tanggal_perkawinan', 'DESC')
         ->limit(3)
         ->findAll();

      $peristiwa = array_merge($kelahiran, $kematian, $perkawinan);
      usort($peristiwa, function ($a, $b) {
         return strtotime($b['tanggal']) - strtotime($a['tanggal']);
      });

      return array_slice($peristiwa, 0, 5);
   }
}
