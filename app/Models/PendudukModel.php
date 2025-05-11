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

   public function getStatistikPendidikan()
   {
      return $this->db->query("
        SELECT pendidikan_terakhir as pendidikan, COUNT(*) as jumlah
        FROM penduduk
        WHERE status_hidup = 1
        GROUP BY pendidikan_terakhir
        ORDER BY jumlah DESC
    ")->getResultArray();
   }

   public function getStatistikPekerjaan()
   {
      return $this->db->query("
        SELECT pekerjaan, COUNT(*) as jumlah
        FROM penduduk
        WHERE status_hidup = 1
        GROUP BY pekerjaan
        ORDER BY jumlah DESC
        LIMIT 5
    ")->getResultArray();
   }

   // app/Models/PendudukModel.php
   public function getStatistikJenisKelamin()
   {
      $total = $this->where('status_hidup', 1)->countAllResults();
      $laki = $this->where('status_hidup', 1)
         ->where('jenis_kelamin', 'L')
         ->countAllResults();
      $perempuan = $total - $laki;

      return [
         'total' => $total,
         'laki' => $laki,
         'perempuan' => $perempuan,
         'persen_laki' => $total > 0 ? round(($laki / $total) * 100) : 0,
         'persen_perempuan' => $total > 0 ? round(($perempuan / $total) * 100) : 0
      ];
   }

   public function getStatistikJenisKelaminArr()
   {
      return $this->db->query("
        SELECT 
            jenis_kelamin,
            COUNT(*) as jumlah
        FROM penduduk
        WHERE status_hidup = 1
        GROUP BY jenis_kelamin
    ")->getResultArray();
   }

   public function getStatistikUsiaProduktif()
   {
      $total = $this->where('status_hidup', 1)->countAllResults();

      if ($total == 0) {
         return [
            'produktif' => 0,
            'total' => 0,
            'persentase' => 0
         ];
      }

      $produktif = $this->where('status_hidup', 1)
         ->where("FLOOR(DATEDIFF(CURRENT_DATE, tanggal_lahir)/365) BETWEEN 17 AND 60")
         ->countAllResults();

      return [
         'produktif' => $produktif,
         'total' => $total,
         'persentase' => round(($produktif / $total) * 100, 2)
      ];
   }

   public function getRasioJenisKelamin()
   {
      $result = $this->db->query("
        SELECT 
            jenis_kelamin,
            COUNT(*) as jumlah,
            ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM penduduk WHERE status_hidup = 1), 2) as persentase
        FROM penduduk
        WHERE status_hidup = 1
        GROUP BY jenis_kelamin
    ")->getResultArray();

      $rasio = [
         'L' => ['jumlah' => 0, 'persentase' => 0],
         'P' => ['jumlah' => 0, 'persentase' => 0]
      ];

      foreach ($result as $row) {
         $rasio[$row['jenis_kelamin']] = [
            'jumlah' => $row['jumlah'],
            'persentase' => $row['persentase']
         ];
      }

      return $rasio;
   }

   public function getStatistikUsia()
   {
      return $this->db->query("
        SELECT 
            FLOOR(DATEDIFF(CURRENT_DATE, tanggal_lahir)/365) DIV 10 * 10 as range_usia,
            COUNT(*) as jumlah
        FROM penduduk
        WHERE status_hidup = 1
        GROUP BY range_usia
        ORDER BY range_usia
    ")->getResultArray();
   }

   public function getStatistikStatusPerkawinan()
   {
      return $this->db->query("
        SELECT 
            status_perkawinan,
            COUNT(*) as jumlah
        FROM penduduk
        WHERE status_hidup = 1
        GROUP BY status_perkawinan
    ")->getResultArray();
   }
}
