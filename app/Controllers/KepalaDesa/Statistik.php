<?php

namespace App\Controllers\KepalaDesa;

use App\Controllers\BaseController;
use App\Models\PendudukModel;
use App\Models\KelahiranModel;
use App\Models\KematianModel;

class Statistik extends BaseController
{
   protected $pendudukModel;
   protected $kelahiranModel;
   protected $kematianModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      $this->kelahiranModel = new KelahiranModel();
      $this->kematianModel = new KematianModel();
   }

   public function index()
   {
      $type = $this->request->getGet('type') ?? 'penduduk';
      $year = $this->request->getGet('tahun') ?? date('Y');

      $data = [
         'title' => 'Statistik ' . ucfirst($type),
         'type' => $type,
         'year' => $year,
         'years' => range(date('Y') - 5, date('Y') + 1),
      ];

      switch ($type) {
         case 'penduduk':
            $data['statistik'] = $this->getStatistikPenduduk($year);
            break;
         case 'kelahiran':
            $data['statistik'] = $this->getStatistikKelahiran($year);
            break;
         case 'kematian':
            $data['statistik'] = $this->getStatistikKematian($year);
            break;
      }


      return view('kepala_desa/statistik/index', $data);
   }

   private function getStatistikPenduduk($year)
   {
      return [
         'pendidikan' => $this->pendudukModel->getStatistikPendidikan(),
         'pekerjaan' => $this->pendudukModel->getStatistikPekerjaan(),
         'usia' => $this->pendudukModel->getStatistikUsia(),
         'jenis_kelamin' => $this->pendudukModel->getStatistikJenisKelaminArr(),
         'perkawinan' => $this->pendudukModel->getStatistikStatusPerkawinan(),
         'usia_produktif' => $this->pendudukModel->getStatistikUsiaProduktif(),
         'rasio_gender' => $this->pendudukModel->getRasioJenisKelamin()
      ];
   }

   private function getStatistikKelahiran($year)
   {
      return $this->kelahiranModel->getStatistikTahunan($year);
   }

   private function getStatistikKematian($year)
   {
      // Data bulanan
      $bulananQuery = $this->kematianModel
         ->select("MONTH(tanggal_meninggal) as bulan, COUNT(*) as jumlah")
         ->where("YEAR(tanggal_meninggal)", $year)
         ->groupBy("bulan")
         ->orderBy("bulan")
         ->findAll();

      // Format data untuk semua bulan
      $bulanan = [];
      for ($i = 1; $i <= 12; $i++) {
         $bulanan[$i] = ['bulan' => $i, 'jumlah' => 0];
      }

      foreach ($bulananQuery as $item) {
         $bulanan[$item['bulan']] = $item;
      }

      // Data penyebab
      $penyebab = $this->kematianModel
         ->select("penyebab, COUNT(*) as jumlah")
         ->where("YEAR(tanggal_meninggal)", $year)
         ->groupBy("penyebab")
         ->orderBy("jumlah", "DESC")
         ->limit(5)
         ->findAll();

      if (empty($penyebab)) {
         $penyebab = [['penyebab' => 'Tidak ada data', 'jumlah' => 0]];
      }

      return [
         'bulanan' => array_values($bulanan), // Pastikan array sequential
         'penyebab' => $penyebab,
         'total' => array_sum(array_column($bulananQuery, 'jumlah'))
      ];
   }
}
