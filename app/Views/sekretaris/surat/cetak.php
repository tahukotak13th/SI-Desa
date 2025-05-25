<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?></title>
   <style>
      body {
         font-family: 'Times New Roman', Times, serif;
         margin: 0 32px;
      }

      .kop-surat {
         display: flex;
         align-items: center;
         margin-bottom: 20px;
         border-bottom: 2px solid #000;
         padding: 24px 0 16px 0;
         position: relative;
      }

      #logo-desa {
         height: 100px;
         width: auto;
         margin-right: 20px;
         margin-left: 20px;
      }

      .header-kop {
         position: absolute;
         left: 50%;
         transform: translateX(-50%);
         text-align: center;
         width: 100%;
         max-width: calc(100% - 120px);
      }

      .header-kop h3 {
         font-size: 1.7rem;
         font-weight: bold;
         margin: 0;
         line-height: 1.3;
      }

      .header-kop h4 {
         font-size: 1.7rem;
         margin: 5px 0;
         line-height: 1.3;
      }

      .header-kop p {
         margin: 5px 0 0;
         font-size: 1.2rem;
      }

      .surat-header {
         margin-bottom: 30px;
         position: relative;
      }

      .judul-surat {
         font-family: inherit;
         text-transform: uppercase;
         text-decoration: underline;
         font-size: 1.5rem;
         text-align: center;
         margin-bottom: 5px;
      }

      .nomor-surat {
         text-align: center;
         margin-bottom: 10px;
         margin: 4px 0 0 0;
         font-weight: 500;
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
      <img id="logo-desa" src="<?= base_url('konohalogo.png') ?>" alt="Logo Desa Konoha">
      <div class="header-kop">
         <h3>PEMERINTAH DESA KONOHA</h3>
         <h4>KECAMATAN CONTOH - KABUPATEN CONTOH</h4>
         <p>Alamat: Jln. Mokuton No. 21, Telp. +6281111111111</p>
      </div>
   </div>

   <div class="surat-header">
      <h1 class="judul-surat"><?= htmlspecialchars($surat['nama_surat']) ?></h1>
      <p class="nomor-surat">No: <?= $surat['no_surat'] ?></p>
   </div>

   <div class="isi-surat">
      <?= nl2br($surat['isi_surat']) ?>
   </div>

   <div class="ttd">
      <div>
         <p>Konoha, <?= date('d F Y') ?></p>
         <p>Kepala Desa Konoha</p>
         <br><br><br>
         <p><strong><u>NAMA KEPALA DESA</u></strong></p>
      </div>
      <div class="clear"></div>
   </div>
</body>

</html>