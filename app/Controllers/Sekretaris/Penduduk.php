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

   public function index()
   {
      $data = [
         'title' => 'Data Penduduk',
         'penduduk' => $this->pendudukModel->findAll()
      ];
      return view('sekretaris/penduduk/index', $data);
   }


   public function tambah()
   {
      $data = [
         'title' => 'Tambah Data Penduduk',
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/penduduk/tambah', $data);
   }


   public function simpan()
   {

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
         'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
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


   public function edit($id)
   {
      $data = [
         'title' => 'Edit Data Penduduk',
         'penduduk' => $this->pendudukModel->find($id),
         'validation' => \Config\Services::validation()
      ];
      return view('sekretaris/penduduk/edit', $data);
   }


   public function update($id)
   {
      $currentData = $this->pendudukModel->find($id);
      $newNik = $this->request->getPost('nik');


      $rules = $this->pendudukModel->getValidationRules();

      // cek NIK kalo update NIK
      if ($newNik !== $currentData['nik']) {
         $rules['nik'] = 'required|numeric|exact_length[16]|is_unique[penduduk.nik,id,' . $id . ']';
      } else {
         // biarin klo nik ga di-update
         unset($rules['nik']);
      }


      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'id' => $id,
         'nik' => $newNik,
         'nama_lengkap' => $this->request->getPost('nama_lengkap'),
         'tempat_lahir' => $this->request->getPost('tempat_lahir'),
         'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
         'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
         'agama' => $this->request->getPost('agama'),
         'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
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


   public function hapus($id)
   {
      $this->pendudukModel->delete($id);
      return redirect()->to('/sekretaris/penduduk')->with('success', 'Data penduduk berhasil dihapus');
   }
}
