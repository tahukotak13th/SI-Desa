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
      // Tambahkan validasi
      $surat = $this->suratModel->find($id);
      if (!$surat) {
         return redirect()->back()->with('error', 'Surat tidak ditemukan');
      }

      if ($surat['status'] != 'diajukan') {
         return redirect()->back()->with('error', 'Surat sudah diproses sebelumnya');
      }

      $this->suratModel->approveSurat($id, session()->get('id'));
      return redirect()->to('/kepala-desa/surat')->with('success', 'Surat berhasil disetujui');
   }

   public function reject($id)
   {
      $catatan = $this->request->getPost('catatan');
      $this->suratModel->rejectSurat($id, session()->get('id'), $catatan);
      session()->setFlashdata('success', 'Surat berhasil ditolak');
      return redirect()->to('/kepala-desa/surat');
   }
}
