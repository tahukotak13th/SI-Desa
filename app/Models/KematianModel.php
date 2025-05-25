<?php

namespace App\Models;

use CodeIgniter\Model;

class KematianModel extends Model
{
   protected $table = 'kematian';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'penduduk_id',
      'tanggal_meninggal',
      'penyebab',
      'tempat_meninggal',
      'created_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   protected $validationRules = [
      'penduduk_id' => 'required|numeric|is_not_unique[penduduk.id]',
      'tanggal_meninggal' => 'required|valid_date',
      'penyebab' => 'required|min_length[3]|max_length[100]',
      'tempat_meninggal' => 'required|min_length[3]|max_length[100]'
   ];

   public function getKematianWithPenduduk()
   {
      return $this->select('kematian.*, penduduk.nik, penduduk.nama_lengkap, penduduk.tanggal_lahir')
         ->join('penduduk', 'penduduk.id = kematian.penduduk_id')
         ->orderBy('tanggal_meninggal', 'DESC')
         ->findAll();
   }

   public function updateStatusPenduduk($penduduk_id)
   {
      $pendudukModel = new \App\Models\PendudukModel();
      $perkawinanModel = new \App\Models\PerkawinanModel();

      // Cari data penduduk yang dah mati
      $penduduk = $pendudukModel->find($penduduk_id);

      // Cek status perkawinan 'kawin'
      if ($penduduk['status_perkawinan'] === 'kawin') {
         // Cari data perkawinan aktif
         $perkawinan = $perkawinanModel->where('status', 'Kawin')
            ->groupStart()
            ->where('suami_id', $penduduk_id)
            ->orWhere('istri_id', $penduduk_id)
            ->groupEnd()
            ->first();

         if ($perkawinan) {
            // Update status perkawinan menjadi 'Meninggal'
            $perkawinanModel->update($perkawinan['id'], ['status' => 'Meninggal']);

            // Update status kedua pasangan
            $pendudukModel->update($perkawinan['suami_id'], ['status_perkawinan' => 'cerai_mati']);
            $pendudukModel->update($perkawinan['istri_id'], ['status_perkawinan' => 'cerai_mati']);
         }
      }

      // Update status hidup penduduk
      $pendudukModel->update($penduduk_id, ['status_hidup' => 0]);
   }

   public function getKematianWithPendudukById($id)
   {
      return $this->select('kematian.*, penduduk.nik, penduduk.nama_lengkap, penduduk.tanggal_lahir')
         ->join('penduduk', 'penduduk.id = kematian.penduduk_id')
         ->where('kematian.id', $id)
         ->first();
   }

   public function getStatistikTahunan($year)
   {
      $bulanan = $this->db->query("
        SELECT 
            MONTH(tanggal_meninggal) as bulan,
            COUNT(*) as jumlah
        FROM kematian
        WHERE YEAR(tanggal_meninggal) = ?
        GROUP BY bulan
        ORDER BY bulan
    ", [$year])->getResultArray();

      $penyebab = $this->db->query("
        SELECT 
            penyebab,
            COUNT(*) as jumlah
        FROM kematian
        WHERE YEAR(tanggal_meninggal) = ?
        GROUP BY penyebab
        ORDER BY jumlah DESC
        LIMIT 5
    ", [$year])->getResultArray();

      return [
         'bulanan' => $bulanan,
         'penyebab' => $penyebab,
         'total' => array_sum(array_column($bulanan, 'jumlah'))
      ];
   }

   public function getStatistikBulanan($year)
   {
      return $this->db->query("
        SELECT 
            MONTH(tanggal_meninggal) as bulan,
            COUNT(*) as jumlah
        FROM kematian
        WHERE YEAR(tanggal_meninggal) = ?
        GROUP BY bulan
        ORDER BY bulan
    ", [$year])->getResultArray();
   }

   public function getStatistikPenyebab($year)
   {
      $result = $this->db->query("
        SELECT 
            COALESCE(penyebab, 'Tidak diketahui') as penyebab,
            COUNT(*) as jumlah
        FROM kematian
        WHERE YEAR(tanggal_meninggal) = ?
        GROUP BY penyebab
        ORDER BY jumlah DESC
        LIMIT 5
    ", [$year])->getResultArray();

      return empty($result) ? [['penyebab' => 'Tidak ada data', 'jumlah' => 0]] : $result;
   }
}
