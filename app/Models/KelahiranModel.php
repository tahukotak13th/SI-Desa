<?php

namespace App\Models;

use CodeIgniter\Model;

class KelahiranModel extends Model
{
   protected $table = 'kelahiran';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'penduduk_id',
      'tanggal_lahir',
      'tempat_lahir',
      'berat_badan',
      'panjang_badan',
      'nama_ayah',
      'nama_ibu'
   ];
   protected $useTimestamps = true;

   public function getKelahiranWithPenduduk()
   {
      return $this->select('kelahiran.*, penduduk.nama_lengkap as nama_bayi')
         ->join('penduduk', 'penduduk.id = kelahiran.penduduk_id')
         ->orderBy('tanggal_lahir', 'DESC')
         ->findAll();
   }

   public function getKelahiranByYear($year)
   {
      return $this->where('YEAR(tanggal_lahir)', $year)
         ->countAllResults();
   }

   public function getKelahiranByMonth($month, $year)
   {
      return $this->where('MONTH(tanggal_lahir)', $month)
         ->where('YEAR(tanggal_lahir)', $year)
         ->countAllResults();
   }
}
