<?php

namespace App\Models;

use CodeIgniter\Model;

class PendudukModel extends Model
{
   protected $table = 'penduduk';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'nik',
      'nama_lengkap',
      'tempat_lahir',
      'tanggal_lahir',
      'jenis_kelamin',
      'agama',
      'status_perkawinan',
      'pekerjaan',
      'penghasilan',
      'alamat',
      'rt',
      'rw',
      'dusun',
      'status_hidup'
   ];
   protected $useTimestamps = true;

   public function getPendudukByStatus($status = 1)
   {
      return $this->where('status_hidup', $status)->findAll();
   }

   public function getPendudukByNik($nik)
   {
      return $this->where('nik', $nik)->first();
   }

   public function getPendudukByStatusPerkawinan($status)
   {
      return $this->where('status_perkawinan', $status)->findAll();
   }
}
