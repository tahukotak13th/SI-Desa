<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisSuratModel extends Model
{
   protected $table = 'jenis_surat';
   protected $primaryKey = 'id';
   protected $allowedFields = ['kode_surat', 'nama_surat', 'template', 'kebutuhan_data'];
   protected $useTimestamps = false;

   // Template default untuk semua jenis surat
   private $defaultTemplates = [
      'SK-DOM' => [
         'template' => "Yang bertanda tangan di bawah ini, Kepala Desa Konoha, dengan ini menerangkan bahwa:\n"
            . "Nama\t\t: {{nama}}\n"
            . "NIK\t\t\t: {{nik}}\n"
            . "TTL\t\t\t: {{tempat_lahir}}, {{tanggal_lahir}}\n"
            . "Alamat\t\t: {{alamat}}, RT {{rt}} RW {{rw}}, Dusun {{dusun}}\n\n"
            . "\tAdalah benar merupakan warga yang berdomisili secara tetap di wilayah administratif Desa Konoha, "
            . "dan hingga surat ini dibuat, yang bersangkutan tercatat sebagai penduduk aktif yang telah memenuhi kewajiban administrasi kependudukan.\n\n"
            . "\tDemikian surat keterangan ini dibuat untuk digunakan sebagai kelengkapan administrasi sesuai dengan kebutuhan yang bersangkutan. "
            . "Segala bentuk penyalahgunaan surat ini menjadi tanggung jawab pemegang surat.\n\n",
         'kebutuhan_data' => ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'rt', 'rw', 'dusun'],
         'additional_fields' => [
            'keperluan' => ['type' => 'text', 'required' => true]
         ]
      ],

      'SK-TM' => [
         'template' => "Yang bertanda tangan di bawah ini, Kepala Desa Konoha, dengan ini menerangkan bahwa:\n"
            . "Nama\t\t: {{nama}}\n"
            . "NIK\t\t\t: {{nik}}\n"
            . "Alamat\t\t: {{alamat}}, RT {{rt}} RW {{rw}}, Dusun {{dusun}}\n\n"
            . "Adalah warga tidak mampu di Desa Konoha dan membutuhkan bantuan untuk keperluan ..........",
         'kebutuhan_data' => ['nik', 'nama_lengkap', 'alamat',  'rt', 'rw', 'dusun'],
         'additional_fields' => [
            'keperluan' => ['type' => 'text', 'required' => true]
         ]
      ],

      'SK-PGH' => [
         'template' => "Yang bertanda tangan di bawah ini, Kepala Desa Konoha, dengan ini menerangkan bahwa:\n"
            . "Nama\t\t\t\t: {{nama}}\n"
            . "NIK\t\t\t\t\t: {{nik}}\n"
            . "Alamat\t\t\t\t: {{alamat}}, RT {{rt}} RW {{rw}}, Dusun {{dusun}}\n"
            . "Pekerjaan\t\t\t\t: {{pekerjaan}}\n"
            . "Penghasilan perbulan\t: Rp {{penghasilan}} ({{terbilang}})\n\n"
            . "Data tersebut adalah benar sesuai keadaan sebenarnya dan diberikan untuk keperluan ..........",
         'kebutuhan_data' => ['nik', 'nama_lengkap', 'alamat', 'pekerjaan', 'penghasilan', 'rt', 'rw', 'dusun'],
         'additional_fields' => [
            'keperluan' => ['type' => 'text', 'required' => true],
            'terbilang' => ['type' => 'text', 'required' => true]
         ]
      ],

      'SK-MATI' => [
         'template' => "Yang bertanda tangan di bawah ini, Kepala Desa Konoha, dengan ini menerangkan bahwa:\n"
            . "Nama Alm.\t\t\t: {{nama}}\n"
            . "NIK\t\t\t\t\t: {{nik}}\n"
            . "TTL\t\t\t\t\t: {{tempat_lahir}}, {{tanggal_lahir}}\n"
            . "Alamat\t\t\t\t: {{alamat}}, RT {{rt}} RW {{rw}}, Dusun {{dusun}}\n\n"
            . "Tanggal Meninggal\t\t: {{tanggal_meninggal}}\n"
            . "Penyebab\t\t\t: {{penyebab}}\n"
            . "Tempat Meninggal\t\t: {{tempat_meninggal}}\n\n"
            . "Warga tersebut benar-benar telah meninggal dunia pada hari tersebut di atas.",
         'kebutuhan_data' => ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'rt', 'rw', 'dusun'],
         'additional_fields' => [
            'tanggal_meninggal' => ['type' => 'date', 'required' => true],
            'penyebab' => ['type' => 'text', 'required' => true],
            'tempat_meninggal' => ['type' => 'text', 'required' => true]
         ]
      ],

      'SK-PKRJ' => [
         'template' => "Yang bertanda tangan di bawah ini, Kepala Desa Konoha, dengan ini menerangkan bahwa:\n"
            . "Nama\t\t: {{nama}}\n"
            . "NIK\t\t\t: {{nik}}\n"
            . "TTL\t\t\t: {{tempat_lahir}}, {{tanggal_lahir}}\n"
            . "Alamat\t\t: {{alamat}}, RT {{rt}} RW {{rw}}, Dusun {{dusun}}\n\n"
            . "Adalah benar-benar warga kami yang saat ini tidak memiliki pekerjaan tetap.\n"
            . "Surat ini diberikan untuk keperluan ..........",
         'kebutuhan_data' => ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'rt', 'rw', 'dusun'],
         'additional_fields' => [
            'keperluan' => ['type' => 'text', 'required' => true]
         ]
      ]
   ];

   public function getTemplateByKode($kode)
   {
      $jenis = $this->where('kode_surat', $kode)->first();

      if (!$jenis || empty($jenis['template'])) {
         return $this->defaultTemplates[$kode] ?? null;
      }

      return [
         'template' => $jenis['template'],
         'kebutuhan_data' => json_decode($jenis['kebutuhan_data'], true)
            ?? $this->defaultTemplates[$kode]['kebutuhan_data']
            ?? []
      ];
   }
}
