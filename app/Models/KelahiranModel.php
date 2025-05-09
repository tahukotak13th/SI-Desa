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
      'nama_ibu',
      'created_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   protected $validationRules = [
      'penduduk_id' => 'permit_empty|numeric|is_not_unique[penduduk.id]',
      'tanggal_lahir' => 'required|valid_date',
      'tempat_lahir' => 'required|min_length[3]|max_length[100]',
      'berat_badan' => 'permit_empty|decimal',
      'panjang_badan' => 'permit_empty|decimal',
      'nama_ayah' => 'required|min_length[3]|max_length[100]',
      'nama_ibu' => 'required|min_length[3]|max_length[100]'
   ];

   public function getKelahiranWithPenduduk()
   {
      return $this->select('kelahiran.*, penduduk.nik, penduduk.nama_lengkap')
         ->join('penduduk', 'penduduk.id = kelahiran.penduduk_id', 'left')
         ->orderBy('tanggal_lahir', 'DESC')
         ->findAll();
   }

   public function createPenduduk($data)
   {
      $pendudukModel = new \App\Models\PendudukModel();
      return $pendudukModel->insert($data);
   }
}
