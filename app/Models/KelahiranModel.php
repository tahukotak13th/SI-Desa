<?php

namespace App\Models;

use CodeIgniter\Model;

class KelahiranModel extends Model
{
   protected $table = 'kelahiran';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'penduduk_id',
      'perkawinan_id',
      'tanggal_lahir',
      'tempat_lahir',
      'berat_badan',
      'panjang_badan',
      'nama_ayah',
      'nama_ibu',
      'created_at',
      'updated_at'
   ];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   protected $validationRules = [
      'penduduk_id' => 'permit_empty|numeric|is_not_unique[penduduk.id]',
      'perkawinan_id' => 'required|numeric|is_not_unique[perkawinan.id]',
      'tanggal_lahir' => 'required|valid_date',
      'tempat_lahir' => 'required|min_length[3]|max_length[100]',
      'berat_badan' => 'permit_empty|decimal',
      'panjang_badan' => 'permit_empty|decimal',
      // nama_ayah dan nama_ibu tidak divalidasi karena akan diisi otomatis
   ];

   public function getKelahiranWithPenduduk()
   {
      return $this->select('kelahiran.*, penduduk.nik, penduduk.nama_lengkap, 
                            suami.nama_lengkap as nama_ayah, istri.nama_lengkap as nama_ibu')
         ->join('penduduk', 'penduduk.id = kelahiran.penduduk_id', 'left')
         ->join('perkawinan', 'perkawinan.id = kelahiran.perkawinan_id', 'left')
         ->join('penduduk suami', 'suami.id = perkawinan.suami_id', 'left')
         ->join('penduduk istri', 'istri.id = perkawinan.istri_id', 'left')
         ->orderBy('tanggal_lahir', 'DESC')
         ->findAll();
   }
   public function getKelahiranWithDetail($id)
   {
      return $this->select('kelahiran.*, 
                        penduduk.nik, penduduk.nama_lengkap, penduduk.jenis_kelamin,
                        penduduk.agama, penduduk.alamat, penduduk.rt, penduduk.rw, penduduk.dusun,
                        suami.nik as nik_ayah, suami.nama_lengkap as nama_ayah,
                        istri.nik as nik_ibu, istri.nama_lengkap as nama_ibu')
         ->join('penduduk', 'penduduk.id = kelahiran.penduduk_id')
         ->join('perkawinan', 'perkawinan.id = kelahiran.perkawinan_id')
         ->join('penduduk suami', 'suami.id = perkawinan.suami_id')
         ->join('penduduk istri', 'istri.id = perkawinan.istri_id')
         ->where('kelahiran.id', $id)
         ->first();
   }

   public function createPenduduk($data)
   {
      $pendudukModel = new \App\Models\PendudukModel();
      return $pendudukModel->insert($data);
   }

   public function getStatistikTahunan($year)
   {
      $bulanan = $this->db->query("
        SELECT 
            MONTH(tanggal_lahir) as bulan,
            COUNT(*) as jumlah
        FROM kelahiran
        WHERE YEAR(tanggal_lahir) = ?
        GROUP BY bulan
        ORDER BY bulan
    ", [$year])->getResultArray();

      $jenisKelamin = $this->db->query("
        SELECT 
            p.jenis_kelamin,
            COUNT(*) as jumlah
        FROM kelahiran k
        JOIN penduduk p ON k.penduduk_id = p.id
        WHERE YEAR(k.tanggal_lahir) = ?
        GROUP BY p.jenis_kelamin
    ", [$year])->getResultArray();

      return [
         'bulanan' => $bulanan,
         'jenis_kelamin' => $jenisKelamin,
         'total' => array_sum(array_column($bulanan, 'jumlah'))
      ];
   }
}
