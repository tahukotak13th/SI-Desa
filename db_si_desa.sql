-- Database: db_si_desa
-- PHP versi 8.2.12
-- Server version: 11.4.3-MariaDB

-- Tabel Users untuk semua level user
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    level ENUM('admin', 'sekretaris', 'kepala_desa') NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Pejabat Desa
CREATE TABLE pejabat_desa (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    nama_lengkap VARCHAR(100) NOT NULL, -- Kolom baru
    jabatan VARCHAR(100) NOT NULL,
    periode_mulai DATE NOT NULL,
    periode_selesai DATE,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Updated Penduduk table with education columns
CREATE TABLE penduduk (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(16) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    agama VARCHAR(20) NOT NULL,
    status_perkawinan ENUM('belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati') NOT NULL,
    pekerjaan VARCHAR(50) NOT NULL,
    penghasilan DECIMAL(15,2),
    alamat TEXT NOT NULL,
    rt VARCHAR(3) NOT NULL,
    rw VARCHAR(3) NOT NULL,
    dusun VARCHAR(50) NOT NULL,
    status_hidup TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Kelahiran
CREATE TABLE kelahiran (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    penduduk_id INT(11) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    berat_badan DECIMAL(5,2),
    panjang_badan DECIMAL(5,2),
    nama_ayah VARCHAR(100) NOT NULL,
    nama_ibu VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penduduk_id) REFERENCES penduduk(id) ON DELETE CASCADE
);

-- Tabel Kematian
CREATE TABLE kematian (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    penduduk_id INT(11) NOT NULL,
    tanggal_meninggal DATE NOT NULL,
    penyebab VARCHAR(100),
    tempat_meninggal VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penduduk_id) REFERENCES penduduk(id) ON DELETE CASCADE
);

-- Tabel Perkawinan
CREATE TABLE perkawinan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    suami_id INT(11) NOT NULL,
    istri_id INT(11) NOT NULL,
    tanggal_perkawinan DATE NOT NULL,
    tempat_perkawinan VARCHAR(100) NOT NULL,
    status VARCHAR(20) DEFAULT 'kawin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (suami_id) REFERENCES penduduk(id),
    FOREIGN KEY (istri_id) REFERENCES penduduk(id)
);

-- Tabel Jenis Surat
CREATE TABLE jenis_surat (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_surat VARCHAR(10) NOT NULL UNIQUE,
    nama_surat VARCHAR(100) NOT NULL,
    template TEXT
);

-- Tabel Surat Keterangan
CREATE TABLE surat_keterangan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    no_surat VARCHAR(50) NOT NULL UNIQUE,
    jenis_surat_id INT(11) NOT NULL,
    penduduk_id INT(11) NOT NULL,
    sekretaris_id INT(11) NOT NULL,
    kepala_desa_id INT(11),
    isi_surat TEXT NOT NULL,
    status ENUM('draft', 'diajukan', 'disetujui', 'ditolak') DEFAULT 'draft',
    tanggal_pengajuan DATE,
    tanggal_approval DATE,
    catatan TEXT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (jenis_surat_id) REFERENCES jenis_surat(id),
    FOREIGN KEY (penduduk_id) REFERENCES penduduk(id),
    FOREIGN KEY (sekretaris_id) REFERENCES users(id),
    FOREIGN KEY (kepala_desa_id) REFERENCES users(id)
);

-- Tabel Log Aktivitas
CREATE TABLE log_aktivitas (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    aktivitas VARCHAR(100) NOT NULL,
    tabel_terkait VARCHAR(50),
    id_entitas INT(11),
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Data awal untuk jenis surat
INSERT INTO jenis_surat (kode_surat, nama_surat) VALUES 
('SK-DOM', 'Surat Keterangan Domisili'),
('SK-TM', 'Surat Keterangan Tidak Mampu'),
('SK-PGH', 'Surat Keterangan Penghasilan'),
('SK-MATI', 'Surat Keterangan Kematian'),
('SK-PKRJ', 'Surat Keterangan Status Pekerjaan');

-- Data awal untuk admin
INSERT INTO users (username, password, nama_lengkap, email, level) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Sistem', 'admin@desa.id', 'admin');

-- Alter table penduduk
ALTER TABLE penduduk 
ADD COLUMN pendidikan_terakhir ENUM('Tidak Bersekolah','SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'S1', 'S2', 'S3') AFTER agama;

DROP TABLE pendidikan;

-- Alter table perkawinan
ALTER TABLE perkawinan 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Alter table kematian
ALTER TABLE kematian ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;