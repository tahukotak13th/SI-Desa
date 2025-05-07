<?php

namespace App\Controllers\Sekretaris;

use App\Controllers\BaseController;
use App\Models\PendudukModel;

class Penduduk extends BaseController
{
   protected $pendudukModel;

   public function __construct()
   {
      $this->pendudukModel = new PendudukModel();
      helper('form');
   }

   // Menampilkan semua data
   public function index()
   {
      $data = [
         'title' => 'Data Penduduk',
         'penduduk' => $this->pendudukModel->findAll()
      ];
      return view('sekretaris/penduduk/index', $data);
   }

   // Form tambah data
   public function tambah()
   {
      $data = [
         'title' => 'Tambah Data Penduduk',
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/penduduk/tambah', $data);
   }

   // Proses simpan data
   public function simpan()
   {
      // Validasi input
      if (!$this->validate($this->pendudukModel->getValidationRules())) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'nik' => $this->request->getPost('nik'),
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
         'agama' => $this->request->getPost('agama'),
         'status_perkawinan' => $this->request->getPost('status_perkawinan'),
         'pekerjaan' => $this->request->getPost('pekerjaan'),
         'penghasilan' => $this->request->getPost('penghasilan') ?? 0,
         'alamat' => $this->request->getPost('alamat'),
         'rt' => $this->request->getPost('rt'),
         'rw' => $this->request->getPost('rw'),
         'dusun' => $this->request->getPost('dusun'),
         'status_hidup' => $this->request->getPost('status_hidup') ?? 1
      ];

      $this->pendudukModel->save($data);
      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil ditambahkan');
   }

   // Form edit data
   public function edit($id)
   {
      $data = [
         'title' => 'Edit Data Penduduk',
         'penduduk' => $this->pendudukModel->find($id),
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/penduduk/edit', $data);
   }

   // Proses update data
   public function update($id)
   {

      // Validasi input
      $rules = $this->pendudukModel->getValidationRules();
      $rules['nik'] = str_replace('{id}', $id, $rules['nik']); // Handle unique validation for update

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'id' => $id,
         'nik' => $this->request->getPost('nik'),
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
         'agama' => $this->request->getPost('agama'),
         'status_perkawinan' => $this->request->getPost('status_perkawinan'),
         'pekerjaan' => $this->request->getPost('pekerjaan'),
         'penghasilan' => $this->request->getPost('penghasilan') ?? 0,
         'alamat' => $this->request->getPost('alamat'),
         'rt' => $this->request->getPost('rt'),
         'rw' => $this->request->getPost('rw'),
         'dusun' => $this->request->getPost('dusun'),
         'status_hidup' => $this->request->getPost('status_hidup') ?? 1
      ];

      $this->pendudukModel->save($data);
      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil diperbarui');
   }

   // Hapus data
   public function hapus($id)
   {
      $this->pendudukModel->delete($id);
      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil dihapus');
   }
}
