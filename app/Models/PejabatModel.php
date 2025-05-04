<?php

namespace App\Models;

use CodeIgniter\Model;

class PejabatModel extends Model
{
   protected $table = 'pejabat_desa';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'user_id',
      'nama_lengkap',
      'jabatan',
      'periode_mulai',
      'periode_selesai',
      'keterangan'
   ];
   protected $useTimestamps = true;

   public function getPejabatWithUser()
   {
      return $this->select('pejabat_desa.*, users.nama_lengkap, users.username')
         ->join('users', 'users.id = pejabat_desa.user_id')
         ->orderBy('jabatan', 'ASC')
         ->findAll();
   }
}
