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
   protected $useTimestamps = true;

   public function getAllSurat()
   {
      return $this->select('surat_keterangan.*, 
            jenis_surat.nama_surat, jenis_surat.kode_surat,
            penduduk.nama_lengkap as nama_penduduk, penduduk.nik,
            users.nama_lengkap as nama_sekretaris')
         ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
         ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
         ->join('users', 'users.id = surat_keterangan.sekretaris_id')
         ->orderBy('created_at', 'DESC')
         ->findAll();
   }

   public function getSuratById($id)
   {
      return $this->select('surat_keterangan.*, 
            jenis_surat.nama_surat, jenis_surat.kode_surat, jenis_surat.template,
            penduduk.nama_lengkap as nama_penduduk, penduduk.nik, 
            penduduk.tempat_lahir, penduduk.tanggal_lahir,
            penduduk.jenis_kelamin, penduduk.agama, 
            penduduk.pekerjaan, penduduk.alamat, penduduk.rt, 
            penduduk.rw, penduduk.dusun,
            sekretaris.nama_lengkap as nama_sekretaris,
            kepala_desa.nama_lengkap as nama_kepala_desa')
         ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
         ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
         ->join('users sekretaris', 'sekretaris.id = surat_keterangan.sekretaris_id')
         ->join('users kepala_desa', 'kepala_desa.id = surat_keterangan.kepala_desa_id', 'left')
         ->where('surat_keterangan.id', $id)
         ->first();
   }

   public function getSuratTerbaru()
   {
      return $this->select('surat_keterangan.*, 
            jenis_surat.nama_surat, 
            penduduk.nama_lengkap as nama_penduduk')
         ->join('jenis_surat', 'jenis_surat.id = surat_keterangan.jenis_surat_id')
         ->join('penduduk', 'penduduk.id = surat_keterangan.penduduk_id')
         ->orderBy('created_at', 'DESC')
         ->limit(5)
         ->findAll();
   }

   public function getSuratByStatus($status)
   {
      return $this->where('status', $status)->findAll();
   }

   public function getSuratByJenis($jenisId)
   {
      return $this->where('jenis_surat_id', $jenisId)->findAll();
   }

   public function getCountByStatus($status)
   {
      return $this->where('status', $status)->countAllResults();
   }
}
