-- Membuat Database
CREATE DATABASE IF NOT EXISTS kasir_db;
USE kasir_db;

-- Tabel `menu`
CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    kategori ENUM('Makanan', 'Minuman', 'Dessert') NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    status ENUM('Tersedia', 'Habis') NOT NULL DEFAULT 'Tersedia'
);

-- Tabel `pelanggan`
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(15),
    alamat TEXT,
    email VARCHAR(100),
    poin_reward INT DEFAULT 0
);

-- Tabel `karyawan`
CREATE TABLE karyawan (
    id_karyawan INT AUTO_INCREMENT PRIMARY KEY,
    nama_karyawan VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(15),
    email VARCHAR(100),
    jabatan ENUM('Kasir', 'Chef', 'Waiter', 'Manager') NOT NULL,
    gaji DECIMAL(10,2) NOT NULL,
    tanggal_masuk DATE NOT NULL
);

-- Tabel `meja`
CREATE TABLE meja (
    id_meja INT AUTO_INCREMENT PRIMARY KEY,
    nomor_meja INT NOT NULL,
    kapasitas INT NOT NULL,
    status ENUM('Kosong', 'Terisi') NOT NULL DEFAULT 'Kosong'
);

-- Tabel `transaksi`
CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    id_karyawan INT,
    id_meja INT,
    tanggal DATETIME NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    diskon DECIMAL(10,2) DEFAULT 0,
    total_bayar DECIMAL(10,2) NOT NULL,
    metode_pembayaran ENUM('Cash', 'Debit', 'QRIS', 'Transfer') NOT NULL,
    status ENUM('Lunas', 'Pending') NOT NULL DEFAULT 'Pending',
    uang_dibayar DECIMAL(10,2), -- Jumlah uang yang dibayarkan
    kembalian DECIMAL(10,2),    -- Kembalian yang diberikan
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan),
    FOREIGN KEY (id_meja) REFERENCES meja(id_meja)
);

-- Tabel `detail_transaksi`
CREATE TABLE detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_menu INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);

-- Tabel `stok_bahan`
CREATE TABLE stok_bahan (
    id_bahan INT AUTO_INCREMENT PRIMARY KEY,
    nama_bahan VARCHAR(100) NOT NULL,
    satuan VARCHAR(20) NOT NULL,
    jumlah_stok DECIMAL(10,2) NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_kadaluarsa DATE NOT NULL
);

-- Tabel `resep`
CREATE TABLE resep (
    id_resep INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    id_bahan INT NOT NULL,
    jumlah_bahan DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu),
    FOREIGN KEY (id_bahan) REFERENCES stok_bahan(id_bahan)
);

-- Tabel `promo`
CREATE TABLE promo (
    id_promo INT AUTO_INCREMENT PRIMARY KEY,
    kode_promo VARCHAR(50) NOT NULL UNIQUE,
    nama_promo VARCHAR(100) NOT NULL,
    persentase_diskon DECIMAL(5,2) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_berakhir DATE NOT NULL
);

-- Tabel `laporan_keuangan`
CREATE TABLE laporan_keuangan (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    total_pendapatan DECIMAL(10,2) NOT NULL,
    total_pengeluaran DECIMAL(10,2) NOT NULL,
    laba_bersih DECIMAL(10,2) NOT NULL
);

-- Trigger untuk validasi pembayaran di tabel `transaksi`
DELIMITER $$

CREATE TRIGGER before_insert_transaksi
BEFORE INSERT ON transaksi
FOR EACH ROW
BEGIN
    IF NEW.uang_dibayar < NEW.total_bayar THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Uang yang dibayarkan tidak cukup';
    ELSEIF NEW.uang_dibayar IS NOT NULL THEN
        SET NEW.kembalian = NEW.uang_dibayar - NEW.total_bayar;
    END IF;
END$$

DELIMITER ;

-- Data untuk tabel `menu`
INSERT INTO menu (nama_menu, harga, kategori, deskripsi, gambar, status)
VALUES 
('Nasi Goreng', 15000, 'Makanan', 'Nasi goreng spesial dengan telur dan ayam', 'images/nasi_goreng.jpg', 'Tersedia'),
('Ayam Goreng', 20000, 'Makanan', 'Ayam goreng crispy dengan sambal pedas', 'images/ayam_goreng.jpg', 'Tersedia'),
('Es Teh Manis', 5000, 'Minuman', 'Es teh manis segar', 'images/es_teh.jpg', 'Tersedia'),
('Pudding Coklat', 10000, 'Dessert', 'Pudding coklat lezat dengan saus vanila', 'images/pudding.jpg', 'Habis');

-- Data untuk tabel `pelanggan`
INSERT INTO pelanggan (nama_pelanggan, no_telepon, alamat, email, poin_reward)
VALUES 
('Andi', '08123456789', 'Jl. Sudirman No. 10', 'andi@example.com', 100),
('Budi', '08567890123', 'Jl. Merdeka No. 5', 'budi@example.com', 50),
('Citra', '08765432109', 'Jl. Diponegoro No. 20', 'citra@example.com', 200),
('Dewi', '08901234567', 'Jl. Gatot Subroto No. 15', 'dewi@example.com', 0);

-- Data untuk tabel `karyawan`
INSERT INTO karyawan (nama_karyawan, no_telepon, email, jabatan, gaji, tanggal_masuk)
VALUES 
('Eko', '08112233445', 'eko@example.com', 'Kasir', 3000000, '2023-01-01'),
('Fina', '08223344556', 'fina@example.com', 'Chef', 4000000, '2023-02-15'),
('Gita', '08334455667', 'gita@example.com', 'Waiter', 2500000, '2023-03-10'),
('Hadi', '08445566778', 'hadi@example.com', 'Manager', 5000000, '2023-04-01');

-- Data untuk tabel `meja`
INSERT INTO meja (nomor_meja, kapasitas, status)
VALUES 
(1, 4, 'Kosong'),
(2, 6, 'Terisi'),
(3, 2, 'Kosong'),
(4, 8, 'Kosong'),
(5, 4, 'Terisi');

-- Data untuk tabel `transaksi`
INSERT INTO transaksi (id_pelanggan, id_karyawan, id_meja, tanggal, total_harga, diskon, total_bayar, metode_pembayaran, status, uang_dibayar, kembalian)
VALUES 
(1, 1, 5, '2023-10-01 12:30:00', 50000, 5000, 45000, 'Cash', 'Lunas', 50000, 5000),
(2, 3, 2, '2023-10-01 13:00:00', 30000, 0, 30000, 'Debit', 'Lunas', 30000, 0),
(3, 1, 3, '2023-10-01 14:00:00', 25000, 2500, 22500, 'QRIS', 'Pending', NULL, NULL),
(4, 2, 1, '2023-10-02 11:00:00', 40000, 4000, 36000, 'Cash', 'Lunas', 40000, 4000);

-- Data untuk tabel `detail_transaksi`
INSERT INTO detail_transaksi (id_transaksi, id_menu, jumlah, subtotal)
VALUES 
(1, 1, 2, 30000),
(1, 3, 2, 10000),
(2, 2, 1, 20000),
(2, 3, 2, 10000),
(3, 1, 1, 15000),
(3, 4, 1, 10000),
(4, 2, 2, 40000);

-- Data untuk tabel `stok_bahan`
INSERT INTO stok_bahan (nama_bahan, satuan, jumlah_stok, harga_satuan, tanggal_masuk, tanggal_kadaluarsa)
VALUES 
('Beras', 'kg', 50, 12000, '2023-09-01', '2024-09-01'),
('Ayam', 'kg', 20, 30000, '2023-09-10', '2023-12-10'),
('Minyak Goreng', 'liter', 10, 15000, '2023-08-20', '2024-08-20'),
('Teh Bubuk', 'kg', 5, 50000, '2023-07-15', '2024-07-15');

-- Data untuk tabel `resep`
INSERT INTO resep (id_menu, id_bahan, jumlah_bahan)
VALUES 
(1, 1, 0.5), -- Nasi Goreng membutuhkan 0.5 kg beras
(1, 3, 0.1), -- Nasi Goreng membutuhkan 0.1 liter minyak goreng
(2, 2, 0.3), -- Ayam Goreng membutuhkan 0.3 kg ayam
(2, 3, 0.2), -- Ayam Goreng membutuhkan 0.2 liter minyak goreng
(3, 4, 0.05); -- Es Teh Manis membutuhkan 0.05 kg teh bubuk

-- Data untuk tabel `promo`
INSERT INTO promo (kode_promo, nama_promo, persentase_diskon, tanggal_mulai, tanggal_berakhir)
VALUES 
('PROMO10', 'Diskon 10%', 10.00, '2023-10-01', '2023-10-31'),
('PROMO20', 'Diskon 20%', 20.00, '2023-10-15', '2023-10-20'),
('HEMAT50', 'Diskon 50%', 50.00, '2023-11-01', '2023-11-10');

-- Data untuk tabel `laporan_keuangan`
INSERT INTO laporan_keuangan (tanggal, total_pendapatan, total_pengeluaran, laba_bersih)
VALUES 
('2023-10-01', 120000, 30000, 90000),
('2023-10-02', 80000, 20000, 60000),
('2023-10-03', 150000, 40000, 110000),
('2023-10-04', 200000, 50000, 150000);