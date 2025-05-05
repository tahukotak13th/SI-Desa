<?php

namespace App\Models;

use CodeIgniter\Model;

class PerkawinanModel extends Model
{
   protected $table = 'perkawinan';
   protected $primaryKey = 'id';
   protected $allowedFields = ['suami_id', 'istri_id', 'tanggal_perkawinan', 'tempat_perkawinan', 'status'];
   protected $useTimestamps = true;

   public function getPerkawinanWithPasangan()
   {
      return $this->select('perkawinan.*, 
            suami.nama_lengkap as nama_suami, suami.nik as nik_suami,
            istri.nama_lengkap as nama_istri, istri.nik as nik_istri')
         ->join('penduduk suami', 'suami.id = perkawinan.suami_id')
         ->join('penduduk istri', 'istri.id = perkawinan.istri_id')
         ->orderBy('tanggal_perkawinan', 'DESC')
         ->findAll();
   }

   public function getPerkawinanByStatus($status)
   {
      return $this->where('status', $status)->findAll();
   }

   public function getPerkawinanByYear($year)
   {
      return $this->where('YEAR(tanggal_perkawinan)', $year)
         ->countAllResults();
   }
}
