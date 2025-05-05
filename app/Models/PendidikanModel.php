<?php

namespace App\Models;

use CodeIgniter\Model;

class PendidikanModel extends Model
{
   protected $table = 'pendidikan';
   protected $primaryKey = 'id';
   protected $allowedFields = ['penduduk_id', 'tingkat_pendidikan', 'nama_instansi', 'tahun_lulus'];
   protected $useTimestamps = false;

   public function getPendidikanByPenduduk($pendudukId)
   {
      return $this->where('penduduk_id', $pendudukId)
         ->orderBy('tahun_lulus', 'DESC')
         ->findAll();
   }

   public function getLatestPendidikan($pendudukId)
   {
      return $this->where('penduduk_id', $pendudukId)
         ->orderBy('tahun_lulus', 'DESC')
         ->first();
   }
}
