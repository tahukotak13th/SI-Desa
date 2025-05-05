<?php

namespace App\Models;

use CodeIgniter\Model;

class KematianModel extends Model
{
   protected $table = 'kematian';
   protected $primaryKey = 'id';
   protected $allowedFields = ['penduduk_id', 'tanggal_meninggal', 'penyebab', 'tempat_meninggal'];
   protected $useTimestamps = true;

   public function getKematianWithPenduduk()
   {
      return $this->select('kematian.*, penduduk.nama_lengkap as nama_penduduk')
         ->join('penduduk', 'penduduk.id = kematian.penduduk_id')
         ->orderBy('tanggal_meninggal', 'DESC')
         ->findAll();
   }

   public function getKematianByYear($year)
   {
      return $this->where('YEAR(tanggal_meninggal)', $year)
         ->countAllResults();
   }

   public function getKematianByMonth($month, $year)
   {
      return $this->where('MONTH(tanggal_meninggal)', $month)
         ->where('YEAR(tanggal_meninggal)', $year)
         ->countAllResults();
   }
}
