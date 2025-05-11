<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratKeteranganModel extends Model
{
   protected $table = 'surat_keterangan';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'no_surat',
      'jenis_surat_id',
      'penduduk_id',
      'sekretaris_id',
      'kepala_desa_id',
      'isi_surat',
      'status',
      'tanggal_pengajuan',
      'tanggal_approval',
      'catatan',
      'file_path'
   ];

   public function getSuratWithDetail($id)
   {
      return $this->select('surat_keterangan.*, jenis_surat.kode_surat, jenis_surat.nama_surat, 
            penduduk.nik, penduduk.nama_lengkap, penduduk.tempat_lahir, penduduk.tanggal_lahir,
            penduduk.alamat, penduduk.rt, penduduk.rw, penduduk.dusun')
         ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
         ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
         ->where('surat_keterangan.id', $id)
         ->first();
   }

   public function validateSurat($data)
   {
      $jenisSurat = $this->db->table('jenis_surat')
         ->where('id', $data['jenis_surat_id'])
         ->get()
         ->getRowArray();

      if (!$jenisSurat) {
         return false;
      }

      $templateData = (new JenisSuratModel())->getTemplateByKode($jenisSurat['kode_surat']);
      $requiredFields = $templateData['kebutuhan_data'] ?? [];

      $penduduk = $this->db->table('penduduk')
         ->where('id', $data['penduduk_id'])
         ->get()
         ->getRowArray();

      foreach ($requiredFields as $field) {
         if (!isset($penduduk[$field]) || empty($penduduk[$field])) {
            return false;
         }
      }

      return true;
   }


   public function getSuratTerbaru($limit = 5)
   {
      return $this->select('surat_keterangan.*, jenis_surat.nama_surat, penduduk.nama_lengkap as nama_penduduk')
         ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
         ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
         ->where('surat_keterangan.status', 'diajukan')
         ->orderBy('surat_keterangan.tanggal_pengajuan', 'DESC')
         ->limit($limit)
         ->find();
   }

   public function approveSurat($id, $kepalaDesaId)
   {
      return $this->update($id, [
         'status' => 'disetujui',
         'kepala_desa_id' => $kepalaDesaId,
         'tanggal_approval' => date('Y-m-d H:i:s')
      ]);
   }

   public function rejectSurat($id, $kepalaDesaId, $catatan)
   {
      return $this->update($id, [
         'status' => 'ditolak',
         'kepala_desa_id' => $kepalaDesaId,
         'tanggal_approval' => date('Y-m-d H:i:s'),
         'catatan' => $catatan
      ]);
   }

   // Tambahkan method ini di SuratKeteranganModel
   public function getSuratByStatus($status, $limit = null)
   {
      $builder = $this->select('surat_keterangan.*, jenis_surat.nama_surat, penduduk.nama_lengkap as nama_penduduk')
         ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
         ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
         ->where('surat_keterangan.status', $status)
         ->orderBy('surat_keterangan.tanggal_pengajuan', 'DESC');

      if ($limit) {
         $builder->limit($limit);
      }

      return $builder->findAll();
   }
}
