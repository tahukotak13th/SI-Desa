<?php

namespace App\Controllers\KepalaDesa;

use App\Controllers\BaseController;
use App\Models\SuratKeteranganModel;

class Surat extends BaseController
{
   protected $suratModel;

   public function __construct()
   {
      $this->suratModel = new SuratKeteranganModel();
   }

   // Di app/Controllers/KepalaDesa/Surat.php
   public function index()
   {
      $data = [
         'title' => 'Persetujuan Surat',
         'surat_menunggu' => $this->suratModel->getSuratByStatus('diajukan', 10),
         'surat_disetujui' => $this->suratModel->getSuratByStatus('disetujui', 5),
         'surat_ditolak' => $this->suratModel->getSuratByStatus('ditolak', 5)
      ];

      return view('kepala_desa/surat/index', $data);
   }

   public function approve($id)
   {
      // Validasi CSRF
      if (!$this->request->is('post')) {
         return redirect()->back()->with('error', 'Invalid request method');
      }

      // Validasi surat
      $surat = $this->suratModel->find($id);
      if (!$surat) {
         return redirect()->back()->with('error', 'Surat tidak ditemukan');
      }

      // Update status surat
      $this->suratModel->update($id, [
         'status' => 'disetujui',
         'kepala_desa_id' => session('id'),
         'tanggal_approval' => date('Y-m-d H:i:s')
      ]);

      return redirect()->to('/kepala-desa/surat')->with('success', 'Surat berhasil disetujui');
   }

   public function reject($id)
   {
      // Validasi CSRF
      if (!$this->request->is('post')) {
         return redirect()->back()->with('error', 'Invalid request method');
      }

      $catatan = $this->request->getPost('catatan');

      // Validasi surat
      $surat = $this->suratModel->find($id);
      if (!$surat) {
         return redirect()->back()->with('error', 'Surat tidak ditemukan');
      }

      // Update status surat
      $this->suratModel->update($id, [
         'status' => 'ditolak',
         'kepala_desa_id' => session('id'),
         'tanggal_approval' => date('Y-m-d H:i:s'),
         'catatan' => $catatan
      ]);

      return redirect()->to('/kepala-desa/surat')->with('success', 'Surat berhasil ditolak');
   }
}
