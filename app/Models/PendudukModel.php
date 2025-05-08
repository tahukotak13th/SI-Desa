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
      'pendidikan_terakhir',
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
   protected $dateFormat = 'datetime';
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   protected $validationRules = [
      'nama_lengkap' => 'required|min_length[3]|max_length[100]',
      'tempat_lahir' => 'required|max_length[50]',
      'tanggal_lahir' => 'required|valid_date',
      'jenis_kelamin' => 'required|in_list[L,P]',
      'agama' => 'required|max_length[20]',
      'pendidikan_terakhir' => 'required|in_list[Tidak Bersekolah,SD/Sederajat,SMP/Sederajat,SMA/Sederajat,S1,S2,S3]',
      'status_perkawinan' => 'required|in_list[belum_kawin,kawin,cerai_hidup,cerai_mati]',
      'pekerjaan' => 'required|max_length[50]',
      'penghasilan' => 'permit_empty|numeric',
      'alamat' => 'required',
      'rt' => 'required|max_length[3]',
      'rw' => 'required|max_length[3]',
      'dusun' => 'required|max_length[50]',
      'status_hidup' => 'required|in_list[0,1]'
   ];

   protected $validationMessages = [
      'nik' => [
         'is_unique' => 'NIK sudah terdaftar',
         'exact_length' => 'NIK harus 16 digit'
      ]
   ];

   public function skipNikValidation(bool $skip = true)
   {
      if ($skip) {
         unset($this->validationRules['nik']);
      } else {
         $this->validationRules['nik'] = 'required|numeric|exact_length[16]|is_unique[penduduk.nik,id,{id}]';
      }
      return $this;
   }

   public function shouldValidateNik(string $newNik, int $id): bool
   {
      $existing = $this->find($id);
      return $existing && $existing->nik !== $newNik;
   }
}
