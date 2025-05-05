<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisSuratModel extends Model
{
   protected $table = 'jenis_surat';
   protected $primaryKey = 'id';
   protected $allowedFields = ['kode_surat', 'nama_surat', 'template'];
   protected $useTimestamps = false;

   public function getJenisByKode($kode)
   {
      return $this->where('kode_surat', $kode)->first();
   }

   public function getTemplateByKode($kode)
   {
      $jenis = $this->where('kode_surat', $kode)->first();
      return $jenis ? $jenis['template'] : null;
   }

   public function getAllKodeSurat()
   {
      return $this->select('kode_surat, nama_surat')->findAll();
   }
}
