<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   protected $table = 'users';
   protected $primaryKey = 'id';
   protected $allowedFields = ['username', 'password', 'nama_lengkap', 'email', 'level', 'is_active', 'created_at', 'updated_at'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   public function getUserByUsername($username)
   {
      return $this->where('username', $username)->first();
   }

   public function insertUser($data)
   {
      $this->db->transStart();

      // Insert ke tabel users
      $this->db->table($this->table)->insert($data);
      $userId = $this->db->insertID();

      // 3 level user ditambahin ke pejabat_desa
      if (in_array($data['level'], ['admin', 'sekretaris', 'kepala_desa'])) {
         $pejabatData = [
            'user_id' => $userId,
            'nama_lengkap' => $data['nama_lengkap'],
            'jabatan' => 'Jabatan ' . $data['level'],
            'periode_mulai' => date('Y-m-d')
         ];
         $this->db->table('pejabat_desa')->insert($pejabatData);
      }

      $this->db->transComplete();
      return $userId;
   }
}
