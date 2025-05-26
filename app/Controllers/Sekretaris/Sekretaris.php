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
