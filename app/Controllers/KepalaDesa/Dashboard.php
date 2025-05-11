<?php

namespace App\Controllers\KepalaDesa;

use App\Controllers\BaseController;
use App\Models\PendudukModel;
use App\Models\SuratKeteranganModel;
use App\Models\KelahiranModel;
use App\Models\KematianModel;

class Dashboard extends BaseController
{
   protected $pendudukModel;
   protected $suratModel;
   protected $kelahiranModel;
   protected $kematianModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      $this->suratModel = new SuratKeteranganModel();
      $this->kelahiranModel = new KelahiranModel();
      $this->kematianModel = new KematianModel();
   }

   public function index()
   {

      $data = [
         'title' => 'Dashboard Kepala Desa',
         'total_penduduk' => $this->pendudukModel->where('status_hidup', 1)->countAllResults(),
         'kelahiran_bulan_ini' => $this->kelahiranModel->where('MONTH(tanggal_lahir)', date('m'))
            ->where('YEAR(tanggal_lahir)', date('Y'))
            ->countAllResults(),
         'kematian_bulan_ini' => $this->kematianModel->where('MONTH(tanggal_meninggal)', date('m'))
            ->where('YEAR(tanggal_meninggal)', date('Y'))
            ->countAllResults(),
         'surat_pending' => $this->suratModel->where('status', 'diajukan')->countAllResults(),
         'surat_terbaru' => $this->suratModel->getSuratTerbaru(),
         'statistik_pendidikan' => $this->pendudukModel->getStatistikPendidikan(),
         'statistik_pekerjaan' => $this->pendudukModel->getStatistikPekerjaan(),
         'statistik_jk' => $this->pendudukModel->getStatistikJenisKelamin(),
         'usia_produktif' => $this->pendudukModel->getStatistikUsiaProduktif()
      ];

      return view('kepala_desa/dashboard', $data);
   }
}
