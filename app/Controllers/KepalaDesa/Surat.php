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

      if (!$this->request->is('post')) {
         return redirect()->back()->with('error', 'Invalid request method');
      }


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

      $surat = $this->suratModel->find($id);
      if (!$surat) {
         return redirect()->back()->with('error', 'Surat tidak ditemukan');
      }

      // Update status surat
      $this->suratModel->update($id, [
         'status' => 'ditolak',
         'kepala_desa_id' => session('id'),
         'tanggal_approval' => date('Y-m-d H:i:s'),
      ]);

      return redirect()->to('/kepala-desa/surat')->with('success', 'Surat berhasil ditolak');
   }

   public function preview($id)
   {
      $surat = $this->suratModel->getSuratWithDetail($id);

      // if (!$surat) {
      //    return $this->response->setJSON([
      //       'success' => false,
      //       'message' => 'Surat tidak ditemukan'
      //    ]);
      // }

      return $this->response->setJSON([
         'success' => true,
         'content' => nl2br(esc($surat['isi_surat']))
      ]);
   }
}
