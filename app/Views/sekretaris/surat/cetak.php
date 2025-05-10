<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?></title>
   <style>
      body {
         font-family: 'Times New Roman', Times, serif;
      }

      .kop-surat {
         border-bottom: 3px solid #000;
         padding-bottom: 15px;
         margin-bottom: 20px;
      }

      .header-kop {
         text-align: center;
      }

      .logo {
         width: 80px;
         height: auto;
      }

      .nomor-surat {
         text-align: right;
         margin-bottom: 30px;
      }

      .isi-surat {
         line-height: 1.8;
         text-align: justify;
      }

      .ttd {
         margin-top: 50px;
         width: 100%;
      }

      .ttd div {
         float: right;
         width: 300px;
         text-align: center;
      }

      .clear {
         clear: both;
      }
   </style>
</head>

<body>
   <div class="kop-surat">
      <div class="header-kop">
         <h3>PEMERINTAH DESA CONTOH</h3>
         <h4>KECAMATAN CONTOH - KABUPATEN CONTOH</h4>
         <p>Alamat: Jl. Contoh No. 123, Telp. 08123456789</p>
      </div>
   </div>

   <div class="nomor-surat">
      <p><strong><?= $surat['no_surat'] ?></strong></p>
   </div>

   <div class="isi-surat">
      <?= nl2br($surat['isi_surat']) ?>
   </div>

   <div class="ttd">
      <div>
         <p>Contoh, <?= date('d F Y') ?></p>
         <p>Kepala Desa Contoh</p>
         <br><br><br>
         <p><strong><u>NAMA KEPALA DESA</u></strong></p>
      </div>
      <div class="clear"></div>
   </div>
</body>

</html>