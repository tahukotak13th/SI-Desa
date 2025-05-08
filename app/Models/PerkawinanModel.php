<?php

namespace App\Models;

use CodeIgniter\Model;

class PerkawinanModel extends Model
{
   protected $table = 'perkawinan';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'suami_id',
      'istri_id',
      'tanggal_perkawinan',
      'tempat_perkawinan',
      'status'
   ];
   protected $useTimestamps = true;
   protected $returnType = 'array';

   protected $validationRules = [
      'suami_id' => 'required|numeric|is_not_unique[penduduk.id]',
      'istri_id' => 'required|numeric|is_not_unique[penduduk.id]',
      'tanggal_perkawinan' => 'required|valid_date',
      'tempat_perkawinan' => 'required|min_length[3]|max_length[100]',
      'status' => 'required|in_list[Kawin,Cerai,Meninggal]'
   ];

   protected $validationMessages = [
      'suami_id' => [
         'is_not_unique' => 'Penduduk laki-laki tidak ditemukan'
      ],
      'istri_id' => [
         'is_not_unique' => 'Penduduk perempuan tidak ditemukan'
      ]
   ];

   public function getPerkawinanWithPasangan()
   {
      return $this->select('perkawinan.*, 
            suami.nik as nik_suami, suami.nama_lengkap as nama_suami, 
            istri.nik as nik_istri, istri.nama_lengkap as nama_istri')
         ->join('penduduk suami', 'suami.id = perkawinan.suami_id')
         ->join('penduduk istri', 'istri.id = perkawinan.istri_id')
         ->orderBy('tanggal_perkawinan', 'DESC')
         ->findAll();
   }

   public function validateGenderPair($suami_id, $istri_id)
   {
      $pendudukModel = new \App\Models\PendudukModel();

      $suami = $pendudukModel->find($suami_id);
      $istri = $pendudukModel->find($istri_id);

      if (!$suami || !$istri) {
         return false;
      }

      return ($suami['jenis_kelamin'] === 'L' && $istri['jenis_kelamin'] === 'P');
   }

   public function getPerkawinanWithDetail($id)
   {
      return $this->select('perkawinan.*, 
        suami.nik as nik_suami, suami.nama_lengkap as nama_suami, suami.jenis_kelamin as jk_suami,
        istri.nik as nik_istri, istri.nama_lengkap as nama_istri, istri.jenis_kelamin as jk_istri')
         ->join('penduduk suami', 'suami.id = perkawinan.suami_id')
         ->join('penduduk istri', 'istri.id = perkawinan.istri_id')
         ->where('perkawinan.id', $id)
         ->first();
   }

   public function updateStatusPenduduk($suami_id, $istri_id, $status_perkawinan)
   {
      $pendudukModel = new \App\Models\PendudukModel();

      // Mapping status dari perkawinan ke penduduk
      $status_mapping = [
         'Kawin' => 'kawin',
         'Cerai' => 'cerai_hidup',
         'Meninggal' => 'cerai_mati'
      ];

      $status = $status_mapping[$status_perkawinan] ?? 'belum_kawin';

      // Update status untuk suami dan istri
      $pendudukModel->update($suami_id, ['status_perkawinan' => $status]);
      $pendudukModel->update($istri_id, ['status_perkawinan' => $status]);
   }
}
