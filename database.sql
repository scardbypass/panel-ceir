-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 19 Okt 2024 pada 10.23
-- Versi server: 10.6.15-MariaDB-cll-lve
-- Versi PHP: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pabr8932_gmj`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` int(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `tipe` enum('INFORMASI','PERINGATAN','PENTING','LAYANAN','PERBAIKAN') NOT NULL,
  `subjek` text NOT NULL,
  `konten` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id`, `date`, `time`, `tipe`, `subjek`, `konten`) VALUES
(12, '2020-07-25', '00:10:31', 'INFORMASI', 'Cara Deposit di Kitadigital SMM Panel Menggunakan GoPay', 'Video ini berisi Panduan Deposit di SMM Panel Kitadigital menggunakan GoPay melalui SMS Banking Bank BNI. Sebenarnya Anda juga bisa melakukan pembayaran langsung dari Aplikasi Gojek, tetapi pada panduan kali ini saya menggunakan SMS Banking untuk transfer nya ke Akun Gopay.\n\nVideo Tutorial : https://www.youtube.com/watch?v=eFBcVEwiID8\n\nAkses layanan gratis untuk semua pengguna dengan sistem penambahan saldo gratis dan giveaway dari Kitadigital. Lama waktu layanan gratis Kitadigital berlaku sesuai aturan yang ditetapkan. Lihat informasi layanan gratis terupdate di halaman Aktivasi Layanan Gratis Kitadigital https://kitadigital.id/blog/\n\nSMM Panel Kitadigital adalah Website yang Menyediakan Produk &amp;amp;amp; Layanan Pemasaran Sosial Media / Social Media Marketing, Sistem Payment Point Online Bank (PPOB), Layanan Pembayaran Elektronik, Layanan Optimalisasi Toko Online, Voucher Game &amp;amp;amp; Produk Digital Lainnya.\n\nLebih dari 1000+ layanan yang disediakan kitadigital.id bisa Anda dapatkan pada halaman Produk dan Layanan https://kitadigital.id/halaman/daftar-harga\n\n'),
(14, '2020-07-25', '15:26:29', 'PENTING', 'Login Pengguna di Perangkat Mobile dalam Tahap Perbaikan', 'Untuk akses login Pengguna Kitadigital di Perangkat Mobile (Android &amp;amp; iOS) masih dalam Tahap Perbaikan.\r\n\r\nSementara untuk Login di Perangkat Desktop masih berjalan dengan Baik.\r\n\r\nTerima kasih'),
(15, '2020-07-25', '18:18:03', 'INFORMASI', 'Peluncuran Kitadigital dan Harga', 'Semua harga produk &amp;amp;amp; layanan kami perbaharui setelah peluncuran sistem aplikasi berbasis beb dan android ke publik secara resmi pada bulan Agustus 2020.\r\n\r\nUntuk saat ini aplikasi web Kitadigital masih dalam tahap pengembangan versi. Kami belum sepenuhnya menerima pesanan.'),
(16, '2020-07-25', '18:22:11', 'PENTING', 'Orderan Produk dan Jasa Layanan', 'Kami hanya mengaktifkan fitur deposit saja saat ini. Orderan produk dan jasa layanan masih dalam pengembangan.\r\n\r\nSemua Fitur Kitadigital yang Tersedia akan diaktifkan setelah peluncuran Web dan Aplikasi Android Kitadigital pada bulan Agustus 2020.'),
(17, '2020-07-25', '18:31:18', 'INFORMASI', 'Metode Pembayaran yang Tersedia', 'Untuk saat ini kami menerima 3 jenis sistem pembayaran:\r\n1. Bank\r\n2. E-Payment\r\n3. Pulsa\r\n\r\nMetode pembayaran:\r\n1. BNI Transfer\r\n2. BRI Transfer\r\n3. Dana Transfer\r\n4. Gopay Transfer\r\n5. OVO Transfer\r\n6. Telkomsel Transfer\r\n\r\nMetode konfirmasi pembayaran\r\n1. Otomatis\r\n2. Manual\r\n\r\nSemua Fitur dan Layanan Kitadigital akan diperbaharui setelah peluncuran Web dan Aplikasi Android pada bulan Agustus 2020.'),
(18, '2020-07-31', '15:00:59', 'LAYANAN', 'Fitur Tarik Saldo ke E-Money Telah Tersedia', 'Sekarang kamu bisa tarik saldo di akunmu dengan metode pembayaran ke e-money, seperti:\r\n1. LinkAja\r\n2. MANDIRI E-TOLL\r\n3. OVO\r\n4. SHOPEE PAY\r\n5. TAPCASH BNI\r\n6. TIX ID\r\n7. DANA\r\n8. dan GO PAY\r\n\r\nSilahkan menuju ke halaman https://kitadigital.id/pemesanan/e-money\r\n\r\n- Customer support Kitadigital'),
(22, '2020-08-16', '13:47:16', 'LAYANAN', 'Sistem Telah Kembali Normal', 'Sistem Telah Kembali Normal. Selamat Bertransaksi..'),
(23, '2020-08-16', '13:48:12', 'INFORMASI', 'Produk dan Layanan Telah Tersedia Kembali', 'Semua Produk dan Layanan Kembali Normal '),
(24, '2020-08-16', '13:49:54', 'LAYANAN', 'Perawatan Sistem (Maintenance) Telah Berakhir', 'Semua Produk dan Layanan Telah kembali Normal'),
(25, '2020-08-17', '12:16:35', 'PERINGATAN', 'Dirgahayu Republik Indonesia', '17 Agustus 1945 - 17 Agustus 2020. &amp;amp;amp;amp;amp;amp;quot;Kemerdekaan bukan tanda untuk berhenti berjuang, tapi tanda untuk berjuang lebih keras dan lebih maju, selamat Hari Kemerdekaan ke-75 RI untuk Rakyat Indonesia&amp;amp;amp;amp;amp;amp;quot;\r\n\r\nGo Ahead Indonesiaku'),
(26, '2020-08-20', '838:59:59', 'LAYANAN', 'Semua Produk Kembali Normal', 'Semua produk dan jasa layanan di KM panel telah kembali normal seperti biasanya. Selamat menikmati layanan kami.\r\n\r\n- KM Panel'),
(27, '2020-09-04', '00:00:00', 'PERINGATAN', 'Peringatan Kembali mengenai Layanan Auto Follower', 'Data statistik kami menunjukkan 27% pengguna auto follower tidak menggunakan akun publik. Oleh sebab itu kami ingatkan kembali untuk merubah status privasi akun ke publik agar layanan auto follower dapat bekerja seperti normalnya. Terima kasih');

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit`
--

CREATE TABLE `deposit` (
  `id` int(10) NOT NULL,
  `kode_deposit` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `nomor_pengirim` varchar(250) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `jumlah_transfer` int(255) NOT NULL,
  `get_saldo` varchar(250) NOT NULL,
  `status` enum('Success','Pending','Error','') NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'WEB',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_bank`
--

CREATE TABLE `deposit_bank` (
  `id` int(10) NOT NULL,
  `kode_deposit` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `nomor_pengirim` varchar(250) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `jumlah_transfer` int(255) NOT NULL,
  `get_saldo` varchar(250) NOT NULL,
  `status` enum('Success','Pending','Error','') NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'WEB',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_emoney`
--

CREATE TABLE `deposit_emoney` (
  `id` int(10) NOT NULL,
  `kode_deposit` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `nomor_pengirim` varchar(250) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `jumlah_transfer` int(255) NOT NULL,
  `get_saldo` varchar(250) NOT NULL,
  `status` enum('Success','Pending','Error','') NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'WEB',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `deposit_emoney`
--

INSERT INTO `deposit_emoney` (`id`, `kode_deposit`, `username`, `tipe`, `provider`, `payment`, `nomor_pengirim`, `tujuan`, `jumlah_transfer`, `get_saldo`, `status`, `place_from`, `date`, `time`) VALUES
(1, '947755', 'scardflasher2', 'EMoney', 'GOPAY', 'GOPAY Konfirmasi Otomatis', '-', '081411105109', 25233, '25233', 'Error', 'Website', '2024-09-19', '06:49:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_epayment`
--

CREATE TABLE `deposit_epayment` (
  `id` int(10) NOT NULL,
  `kode_deposit` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `nomor_pengirim` varchar(250) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `jumlah_transfer` int(255) NOT NULL,
  `get_saldo` varchar(250) NOT NULL,
  `status` enum('Success','Pending','Error','') NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'WEB',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_tsel`
--

CREATE TABLE `deposit_tsel` (
  `id` int(10) NOT NULL,
  `kode_deposit` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `nomor_pengirim` varchar(250) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `jumlah_transfer` int(255) NOT NULL,
  `get_saldo` varchar(250) NOT NULL,
  `status` enum('Success','Pending','Error','') NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'WEB',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_voucher`
--

CREATE TABLE `deposit_voucher` (
  `id` int(10) NOT NULL,
  `kode_deposit` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `nomor_pengirim` varchar(250) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `jumlah_transfer` int(255) NOT NULL,
  `get_saldo` varchar(250) NOT NULL,
  `status` enum('Success','Pending','Error','') NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'WEB',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `edit_sn`
--

CREATE TABLE `edit_sn` (
  `id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `layanan` text NOT NULL,
  `type` enum('all','particular') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaman`
--

CREATE TABLE `halaman` (
  `id` int(2) NOT NULL,
  `konten` text NOT NULL,
  `update_terakhir` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `halaman`
--

INSERT INTO `halaman` (`id`, `konten`, `update_terakhir`) VALUES
(1, '<div class=\"text-center\">\r\nPlatform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital\r\n</div>', '2019-01-21 00:00:00'),
(2, '<div class=\"text-center\">\r\nPlatform Kitadigital menyediakan berbagai layanan Sosial Media Instagram, Facebook, Whatsapp, Twitter, Pinterest, Tiktok, Youtube, SoundCloud dan Spotify. Juga terdapat fitur optimasi toko online Shopee, Bukalapak dan Tokopedia.<br/><br/>\r\nUntuk pembayaran dan transaksi, Kitadigital menyediakan berbagai metode termasuk Payment Point Online Bank (PPOB) untuk Pembayaran Tagihan & Pembelian Produk Digital seperti Pulsa, Internet, Token PLN, Saldo Gopay/Grabpay, Voucher, E-money, dan Cryptocurrency.\r\n</div>', '2019-01-21 00:00:00'),
(3, '<div class=\"text-center\">\r\nBerikut adalah daftar layanan jasa Sosial Media, Toko Online, Payment Point Online Bank (PPOB), dan berbagai produk virtual yang tersedia di Kitadigital.\r\n</div>', '2019-01-21 00:00:00'),
(4, '<div class=\"text-center\">\r\nBerikut adalah daftar partner dan jaringan kerjasama kami yang telah terdaftar.\r\n</div>', '2019-01-21 00:00:00'),
(5, '<div class=\"text-center\">\r\nPlatform Kitadigital menyediakan berbagai fitur pada layanan jasa dan penjualan produk, adapun fitur & teknologi yang mendukung dibalik Aplikasi Kitadigital dapat anda lihat pada halaman dibawah.<br/><br/>\r\nFitur unggulan dan teknologi yang mendukung ini tentunya adalah bagian dari Platform Kitadigital yang telah memberi dukungan penyimpanan dan pengelolaan data, cloud web hosting, metode algoritma, sistem pembayaran, e-payment, dan lainnya yang akan terus kami tingkatkan kualitasnya.\r\n</div>', '2019-01-21 00:45:00'),
(6, '<div class=\"text-center\">\r\nKitadigital menyediakan berbagai metode pembayaran, mulai dari Voucher Deposit, Transfer Pulsa, Cryptocurrency, E-Money Lokal & Global, hingga Bank Transfer.\r\n</div>', '2020-08-10 21:13:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_pendaftaran`
--

CREATE TABLE `harga_pendaftaran` (
  `id` int(2) NOT NULL,
  `level` varchar(50) NOT NULL,
  `harga` double NOT NULL,
  `bonus` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `harga_pendaftaran`
--

INSERT INTO `harga_pendaftaran` (`id`, `level`, `harga`, `bonus`) VALUES
(1, 'Member', 0, 0),
(2, 'Agen', 0, 0),
(3, 'Reseller', 0, 0),
(4, 'Admin', 0, 0),
(5, 'Developers', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `history_saldo`
--

CREATE TABLE `history_saldo` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `aksi` enum('Penambahan Saldo','Pengurangan Saldo') NOT NULL,
  `nominal` double NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_layanan`
--

CREATE TABLE `kategori_layanan` (
  `id` int(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data untuk tabel `kategori_layanan`
--

INSERT INTO `kategori_layanan` (`id`, `nama`, `kode`, `tipe`) VALUES
(6, 'Cekimei', 'Cekimei', 'Digital'),
(7, 'Ceir', 'Ceir', 'Digital'),
(8, 'Unlock', 'Unlock', 'Digital');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_layanan2`
--

CREATE TABLE `kategori_layanan2` (
  `id` int(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_layanan3`
--

CREATE TABLE `kategori_layanan3` (
  `id` int(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan_digital`
--

CREATE TABLE `layanan_digital` (
  `id` int(11) NOT NULL,
  `service_id` varchar(50) NOT NULL,
  `provider_id` varchar(50) NOT NULL,
  `operator` varchar(50) NOT NULL,
  `layanan` text NOT NULL,
  `harga` double NOT NULL,
  `harga_api` double NOT NULL,
  `profit` double NOT NULL,
  `status` enum('Normal','Gangguan') NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data untuk tabel `layanan_digital`
--

INSERT INTO `layanan_digital` (`id`, `service_id`, `provider_id`, `operator`, `layanan`, `harga`, `harga_api`, `profit`, `status`, `provider`, `tipe`, `catatan`) VALUES
(5, 'Carrier', 'Carrier', 'Cekimei', 'Cek Carrier', 1100, 1070, 0, 'Normal', 'roamerimei', 'Digital', ''),
(6, 'History', 'History', 'Ceir', 'Cek History slow', 4950, 4815, 0, 'Normal', 'roamerimei', 'Digital', ''),
(7, 'Historyf', 'Historyf', 'Ceir', 'Cek History fast', 7150, 6955, 0, 'Normal', 'roamerimei', 'Digital', ''),
(8, 'iCloud', 'iCloud', 'Cekimei', 'Cek iCloud', 550, 535, 0, 'Normal', 'roamerimei', 'Digital', ''),
(9, 'Status', 'Status', 'Ceir', 'Cek Status', 3850, 3745, 0, 'Normal', 'roamerimei', 'Digital', ''),
(10, 'Imel', 'Imei', 'Unlock', 'All operator 3B', 7000, 5000, 200, 'Normal', 'MANUAL', 'Digital', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan_pulsa`
--

CREATE TABLE `layanan_pulsa` (
  `id` int(11) NOT NULL,
  `service_id` text NOT NULL,
  `provider_id` varchar(50) NOT NULL,
  `operator` varchar(50) NOT NULL,
  `layanan` text NOT NULL,
  `harga` double NOT NULL,
  `harga_api` double NOT NULL,
  `profit` double NOT NULL,
  `status` enum('Normal','Gangguan') NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `catatan` text NOT NULL,
  `jenis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan_sosmed`
--

CREATE TABLE `layanan_sosmed` (
  `id` int(10) NOT NULL,
  `service_id` int(10) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `layanan` text NOT NULL,
  `catatan` text NOT NULL,
  `min` int(10) NOT NULL,
  `max` int(10) NOT NULL,
  `harga` double NOT NULL,
  `harga_api` double NOT NULL,
  `profit` double NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `provider_id` int(10) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan_sosmed2`
--

CREATE TABLE `layanan_sosmed2` (
  `id` int(10) NOT NULL,
  `service_id` int(10) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `layanan` text NOT NULL,
  `catatan` text NOT NULL,
  `min` int(10) NOT NULL,
  `max` int(10) NOT NULL,
  `harga` double NOT NULL,
  `harga_api` double NOT NULL,
  `profit` double NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `provider_id` int(10) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan_sosmed3`
--

CREATE TABLE `layanan_sosmed3` (
  `id` int(10) NOT NULL,
  `service_id` int(10) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `layanan` text NOT NULL,
  `catatan` text NOT NULL,
  `min` int(10) NOT NULL,
  `max` int(10) NOT NULL,
  `harga` double NOT NULL,
  `harga_api` double NOT NULL,
  `profit` double NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `provider_id` int(10) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log`
--

CREATE TABLE `log` (
  `id` int(4) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `aksi` enum('Login','Logout') NOT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `log`
--

INSERT INTO `log` (`id`, `username`, `aksi`, `ip`, `date`, `time`) VALUES
(83, 'admin', 'Login', '202.67.46.249', '2024-05-26', '09:14:54'),
(84, 'admin', 'Login', '114.5.209.69', '2024-05-26', '13:09:41'),
(85, 'admin', 'Login', '202.67.40.238', '2024-05-26', '14:32:31'),
(86, 'admin', 'Login', '114.5.209.69', '2024-05-26', '14:34:59'),
(87, 'admin', 'Login', '202.67.40.238', '2024-05-26', '14:35:11'),
(88, 'admin', 'Login', '114.5.209.69', '2024-05-26', '14:37:23'),
(89, 'admin', 'Login', '202.67.40.238', '2024-05-26', '14:37:47'),
(90, 'admin', 'Login', '114.5.209.69', '2024-05-26', '14:41:48'),
(91, 'admin', 'Login', '202.67.40.238', '2024-05-26', '14:44:34'),
(92, 'admin', 'Login', '114.5.217.12', '2024-05-26', '17:17:08'),
(93, 'admin', 'Login', '116.206.40.59', '2024-05-26', '17:58:15'),
(94, 'admin', 'Login', '103.142.255.67', '2024-05-26', '20:52:08'),
(95, 'admin', 'Login', '114.5.209.19', '2024-05-27', '14:12:31'),
(96, 'admin', 'Login', '202.67.40.25', '2024-05-27', '14:29:08'),
(97, 'admin', 'Login', '202.67.40.25', '2024-05-27', '14:38:38'),
(98, 'admin', 'Login', '114.5.209.19', '2024-05-27', '14:50:37'),
(99, 'admin', 'Login', '202.67.40.25', '2024-05-27', '15:19:19'),
(100, 'admin', 'Login', '202.67.40.12', '2024-05-30', '19:15:30'),
(101, 'admin', 'Login', '202.67.40.241', '2024-05-31', '20:11:50'),
(102, 'admin', 'Login', '202.67.40.241', '2024-05-31', '20:20:08'),
(103, 'admin', 'Login', '114.5.223.215', '2024-06-20', '12:47:38'),
(104, 'admin', 'Login', '120.188.79.227', '2024-06-20', '21:09:09'),
(105, 'admin', 'Login', '114.5.103.170', '2024-06-29', '08:34:31'),
(106, 'scardflasher', 'Login', '120.188.75.198', '2024-07-07', '22:01:52'),
(107, 'scardflasher2', 'Login', '36.82.13.203', '2024-08-13', '15:55:29'),
(108, 'scardflasher2', 'Login', '36.82.13.203', '2024-08-13', '18:15:36'),
(109, 'maranzano', 'Login', '114.10.136.161', '2024-08-13', '18:54:12'),
(110, 'scardflasher2', 'Login', '114.5.104.239', '2024-08-13', '20:44:56'),
(111, 'maranzano', 'Login', '114.10.136.161', '2024-08-13', '22:05:47'),
(112, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-14', '07:23:51'),
(113, 'maranzano', 'Login', '180.242.233.97', '2024-08-14', '18:54:18'),
(114, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-14', '20:04:01'),
(115, 'maranzano', 'Logout', '114.10.137.78', '2024-08-14', '20:10:45'),
(116, 'Scardflasher2', 'Login', '114.10.137.78', '2024-08-14', '20:11:14'),
(117, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-14', '20:26:50'),
(118, 'maranzano', 'Login', '114.10.137.78', '2024-08-14', '20:30:55'),
(119, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-14', '20:48:14'),
(120, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-14', '21:19:47'),
(121, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-15', '07:09:40'),
(122, 'scardflasher2', 'Login', '180.248.21.64', '2024-08-15', '07:50:01'),
(123, 'scardflasher2', 'Login', '125.163.142.20', '2024-08-15', '08:51:20'),
(124, 'scardflasher2', 'Login', '120.188.78.215', '2024-08-15', '18:04:25'),
(125, 'scardflasher2', 'Login', '114.5.222.172', '2024-08-15', '18:24:34'),
(126, 'scardflasher2', 'Login', '114.5.110.128', '2024-08-15', '22:18:22'),
(127, 'scardflasher2', 'Login', '114.5.110.189', '2024-08-16', '12:30:46'),
(128, 'scardflasher2', 'Login', '114.5.110.169', '2024-08-16', '17:13:52'),
(129, 'scardflasher2', 'Login', '120.188.74.219', '2024-08-16', '19:08:49'),
(130, 'maranzano', 'Login', '180.242.233.97', '2024-08-16', '23:34:22'),
(131, 'maranzano', 'Login', '180.242.233.97', '2024-08-17', '00:29:25'),
(132, 'maranzano', 'Login', '180.242.233.97', '2024-08-17', '10:23:41'),
(133, 'scardflasher2', 'Login', '120.188.79.237', '2024-08-17', '12:54:18'),
(134, 'scardflasher2', 'Login', '180.248.17.77', '2024-08-17', '14:12:13'),
(135, 'maranzano', 'Login', '180.242.233.97', '2024-08-17', '15:51:58'),
(136, 'scardflasher2', 'Login', '180.248.24.0', '2024-08-17', '16:25:40'),
(137, 'scardflasher2', 'Logout', '180.248.24.0', '2024-08-17', '16:27:30'),
(138, 'scardflasher1', 'Login', '180.248.24.0', '2024-08-17', '16:28:07'),
(139, 'scardflasher2', 'Login', '180.248.24.0', '2024-08-17', '16:28:26'),
(140, 'scardflasher2', 'Logout', '180.248.24.0', '2024-08-17', '16:29:20'),
(141, 'scardflasher1', 'Login', '180.248.24.0', '2024-08-17', '16:30:40'),
(142, 'scardflasher1', 'Logout', '180.248.24.0', '2024-08-17', '16:33:28'),
(143, 'Scardflasher2', 'Login', '180.248.24.0', '2024-08-17', '16:33:39'),
(144, 'maranzano', 'Login', '180.242.233.97', '2024-08-17', '19:46:06'),
(145, 'scardflasher2', 'Login', '114.5.105.235', '2024-08-17', '20:04:43'),
(146, 'maranzano', 'Login', '114.10.137.162', '2024-08-17', '20:36:55'),
(147, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-17', '22:14:50'),
(148, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-17', '23:13:59'),
(149, 'maranzano', 'Login', '180.242.233.97', '2024-08-17', '23:58:17'),
(150, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-18', '05:30:13'),
(151, 'scardflasher2', 'Login', '114.5.104.192', '2024-08-18', '08:34:49'),
(152, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-18', '17:22:57'),
(153, 'maranzano', 'Login', '114.10.137.30', '2024-08-18', '17:32:20'),
(154, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-19', '13:29:06'),
(155, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-19', '17:25:37'),
(156, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-19', '18:30:38'),
(157, 'scardflasher2', 'Login', '114.5.103.170', '2024-08-19', '18:56:34'),
(158, 'maranzano', 'Login', '180.242.232.173', '2024-08-20', '03:22:40'),
(159, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '05:28:56'),
(160, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '05:35:28'),
(161, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '05:39:04'),
(162, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '11:48:17'),
(163, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '11:52:10'),
(164, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '11:58:18'),
(165, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '12:29:14'),
(166, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-20', '15:12:25'),
(167, 'scardflasher2', 'Login', '180.248.31.132', '2024-08-21', '06:37:15'),
(168, 'scardflasher2', 'Login', '114.5.104.16', '2024-08-21', '19:15:31'),
(169, 'scardflasher2', 'Login', '114.5.110.185', '2024-08-22', '10:10:23'),
(170, 'scardflasher2', 'Login', '114.5.110.137', '2024-08-22', '14:52:19'),
(171, 'scardflasher2', 'Login', '120.188.79.220', '2024-08-22', '21:22:12'),
(172, 'maranzano', 'Login', '180.242.233.242', '2024-08-22', '22:19:30'),
(173, 'scardflasher2', 'Login', '114.5.103.129', '2024-08-23', '07:16:11'),
(174, 'scardflasher2', 'Login', '114.5.105.249', '2024-08-23', '12:43:41'),
(175, 'scardflasher2', 'Login', '114.5.105.249', '2024-08-23', '13:29:44'),
(176, 'maranzano', 'Login', '180.242.233.242', '2024-08-23', '14:21:32'),
(177, 'scardflasher2', 'Login', '120.188.79.149', '2024-08-23', '14:39:41'),
(178, 'scardflasher2', 'Login', '114.5.222.191', '2024-08-23', '17:04:14'),
(179, 'scardflasher2', 'Login', '114.5.103.138', '2024-08-24', '08:28:08'),
(180, 'scardflasher2', 'Login', '114.5.103.138', '2024-08-24', '08:57:31'),
(181, 'scardflasher2', 'Login', '114.5.104.134', '2024-08-24', '10:31:57'),
(182, 'scardflasher2', 'Login', '114.5.111.251', '2024-08-24', '12:48:33'),
(183, 'scardflasher2', 'Login', '120.188.79.152', '2024-08-24', '14:26:14'),
(184, 'admin', 'Login', '45.8.25.64', '2024-08-25', '02:53:13'),
(185, 'scardflasher2', 'Login', '114.5.223.173', '2024-08-26', '07:41:27'),
(186, 'maranzano', 'Login', '180.242.233.28', '2024-08-26', '17:29:29'),
(187, 'scardflasher2', 'Login', '114.5.102.75', '2024-08-26', '17:38:51'),
(188, 'scardflasher2', 'Login', '114.5.102.120', '2024-08-27', '10:09:20'),
(189, 'scardflasher1', 'Login', '114.5.222.187', '2024-08-27', '12:20:26'),
(190, 'scardflasher1', 'Logout', '114.5.222.187', '2024-08-27', '12:20:43'),
(191, 'scardflasher2', 'Login', '114.5.222.187', '2024-08-27', '12:20:50'),
(192, 'scardflasher2', 'Login', '120.188.74.239', '2024-08-27', '14:20:36'),
(193, 'scardflasher2', 'Login', '120.188.78.239', '2024-08-27', '16:11:34'),
(194, 'scardflasher2', 'Login', '114.5.102.191', '2024-08-27', '19:23:12'),
(195, 'scardflasher2', 'Login', '114.5.104.120', '2024-08-28', '05:38:57'),
(196, 'scardflasher2', 'Login', '114.5.103.147', '2024-08-28', '10:56:12'),
(197, 'scardflasher2', 'Login', '120.188.74.165', '2024-08-28', '16:32:10'),
(198, 'scardflasher2', 'Login', '114.5.223.242', '2024-08-28', '21:17:11'),
(199, 'scardflasher2', 'Login', '120.188.74.190', '2024-08-29', '05:01:47'),
(200, 'scardflasher2', 'Login', '114.5.105.184', '2024-08-29', '11:38:12'),
(201, 'scardflasher2', 'Login', '114.5.102.105', '2024-08-29', '16:45:49'),
(202, 'scardflasher2', 'Login', '120.188.78.243', '2024-08-29', '18:43:11'),
(203, 'scardflasher2', 'Login', '120.188.78.243', '2024-08-29', '19:32:46'),
(204, 'scardflasher2', 'Login', '114.5.111.133', '2024-08-29', '20:46:09'),
(205, 'scardflasher2', 'Login', '114.5.104.179', '2024-08-30', '05:02:18'),
(206, 'Scardflasher2', 'Login', '180.242.233.28', '2024-08-30', '15:15:03'),
(207, 'scardflasher2', 'Login', '114.5.111.193', '2024-08-30', '15:43:14'),
(208, 'Scardflasher2', 'Login', '180.242.233.28', '2024-08-30', '16:09:15'),
(209, 'scardflasher2', 'Login', '114.5.111.193', '2024-08-30', '16:10:03'),
(210, 'Scardflasher2', 'Login', '114.10.137.38', '2024-08-30', '16:13:20'),
(211, 'scardflasher2', 'Login', '114.5.222.227', '2024-08-30', '18:24:24'),
(212, 'scardflasher2', 'Login', '114.5.110.224', '2024-08-30', '21:27:53'),
(213, 'scardflasher2', 'Login', '114.5.103.230', '2024-08-31', '06:51:37'),
(214, 'scardflasher2', 'Login', '114.5.103.248', '2024-08-31', '07:27:11'),
(215, 'scardflasher2', 'Logout', '114.5.103.248', '2024-08-31', '08:16:11'),
(216, 'scardflasher2', 'Login', '114.5.103.248', '2024-08-31', '08:16:52'),
(217, 'Scardflasher2', 'Login', '114.10.152.115', '2024-08-31', '08:33:49'),
(218, 'scardflasher2', 'Login', '114.5.103.248', '2024-08-31', '08:45:53'),
(219, 'scardflasher2', 'Login', '120.188.78.194', '2024-08-31', '09:09:05'),
(220, 'scardflasher2', 'Login', '120.188.78.194', '2024-08-31', '10:32:28'),
(221, 'scardflasher2', 'Login', '114.5.111.210', '2024-08-31', '11:12:25'),
(222, 'Scardflasher2', 'Login', '114.10.29.38', '2024-08-31', '11:44:55'),
(223, 'scardflasher2', 'Login', '114.5.111.210', '2024-08-31', '12:06:08'),
(224, 'scardflasher2', 'Login', '114.5.223.188', '2024-08-31', '13:50:27'),
(225, 'scardflasher2', 'Login', '114.5.223.188', '2024-08-31', '14:05:07'),
(226, 'scardflasher2', 'Login', '114.5.111.131', '2024-09-02', '06:07:09'),
(227, 'scardflasher2', 'Login', '114.5.223.219', '2024-09-05', '18:36:59'),
(228, 'scardflasher2', 'Login', '114.5.110.205', '2024-09-06', '08:50:59'),
(229, 'scardflasher2', 'Login', '120.188.79.176', '2024-09-06', '19:58:54'),
(230, 'scardflasher2', 'Login', '114.5.103.69', '2024-09-07', '19:54:27'),
(231, 'scardflasher2', 'Login', '114.5.105.160', '2024-09-08', '04:26:55'),
(232, 'scardflasher2', 'Login', '120.188.79.161', '2024-09-08', '10:47:20'),
(233, 'scardflasher2', 'Login', '120.188.78.207', '2024-09-08', '18:02:21'),
(234, 'scardflasher2', 'Login', '114.5.110.168', '2024-09-09', '12:29:36'),
(235, 'scardflasher2', 'Login', '114.5.102.37', '2024-09-10', '07:52:38'),
(236, 'scardflasher2', 'Login', '114.5.223.220', '2024-09-10', '16:57:22'),
(237, 'scardflasher2', 'Login', '114.5.222.154', '2024-09-10', '20:48:30'),
(238, 'scardflasher2', 'Login', '114.5.222.154', '2024-09-10', '21:20:16'),
(239, 'scardflasher2', 'Login', '114.5.102.19', '2024-09-10', '22:42:29'),
(240, 'scardflasher2', 'Login', '114.5.102.19', '2024-09-10', '22:48:58'),
(241, 'scardflasher2', 'Login', '114.5.102.19', '2024-09-10', '23:15:36'),
(242, 'Scardflasher2', 'Login', '180.242.233.60', '2024-09-11', '03:22:54'),
(243, 'scardflasher2', 'Login', '120.188.79.255', '2024-09-11', '05:50:20'),
(244, 'scardflasher2', 'Login', '120.188.79.255', '2024-09-11', '06:03:42'),
(245, 'scardflasher2', 'Login', '120.188.79.255', '2024-09-11', '06:05:04'),
(246, 'scardflasher2', 'Login', '120.188.78.158', '2024-09-11', '12:52:59'),
(247, 'Scardflasher2', 'Login', '180.242.233.60', '2024-09-12', '20:33:06'),
(248, 'Scardflasher2', 'Login', '180.242.233.60', '2024-09-12', '23:18:18'),
(249, 'scardflasher2', 'Login', '114.5.102.222', '2024-09-12', '23:19:35'),
(250, 'Scardflasher2', 'Login', '180.242.233.60', '2024-09-12', '23:21:59'),
(251, 'scardflasher2', 'Login', '114.5.102.222', '2024-09-12', '23:22:36'),
(252, 'Scardflasher2', 'Login', '180.242.233.60', '2024-09-13', '03:20:00'),
(253, 'scardflasher2', 'Login', '114.5.222.255', '2024-09-13', '05:36:59'),
(254, 'scardflasher2', 'Login', '114.5.102.126', '2024-09-13', '07:05:24'),
(255, 'scardflasher2', 'Login', '114.5.111.167', '2024-09-13', '11:54:56'),
(256, 'scardflasher2', 'Login', '120.188.74.150', '2024-09-13', '14:03:16'),
(257, 'scardflasher2', 'Login', '114.5.111.170', '2024-09-13', '20:57:48'),
(258, 'Scardflasher2', 'Login', '180.254.66.106', '2024-09-14', '09:10:00'),
(259, 'scardflasher2', 'Login', '114.5.105.101', '2024-09-14', '09:23:23'),
(260, 'scardflasher2', 'Login', '114.5.105.101', '2024-09-14', '10:23:30'),
(261, 'scardflasher2', 'Login', '114.5.110.189', '2024-09-14', '16:49:03'),
(262, 'scardflasher2', 'Login', '120.188.78.205', '2024-09-14', '22:44:32'),
(263, 'scardflasher2', 'Login', '114.5.222.249', '2024-09-15', '16:29:04'),
(264, 'scardflasher2', 'Login', '114.5.222.249', '2024-09-15', '16:29:38'),
(265, 'Scardflasher2', 'Login', '180.254.76.135', '2024-09-15', '21:27:28'),
(266, 'scardflasher2', 'Login', '114.5.102.176', '2024-09-16', '08:55:04'),
(267, 'scardflasher2', 'Login', '114.5.222.187', '2024-09-16', '11:19:12'),
(268, 'scardflasher2', 'Login', '114.5.222.187', '2024-09-16', '12:16:14'),
(269, 'scardflasher2', 'Login', '114.5.222.145', '2024-09-17', '09:37:45'),
(270, 'scardflasher2', 'Login', '114.5.102.67', '2024-09-18', '08:10:38'),
(271, 'scardflasher2', 'Login', '114.5.102.67', '2024-09-18', '08:11:07'),
(272, 'scardflasher2', 'Login', '114.5.222.139', '2024-09-18', '12:08:20'),
(273, 'scardflasher2', 'Login', '120.188.74.128', '2024-09-18', '20:08:38'),
(274, 'scardflasher2', 'Login', '114.5.110.243', '2024-09-19', '05:51:24'),
(275, 'scardflasher2', 'Login', '114.5.110.243', '2024-09-19', '06:24:32'),
(276, 'scardflasher2', 'Login', '114.5.110.243', '2024-09-19', '06:30:23'),
(277, 'scardflasher2', 'Login', '114.5.103.92', '2024-09-19', '20:26:50'),
(278, 'scardflasher2', 'Login', '114.5.103.92', '2024-09-19', '21:00:51'),
(279, 'scardflasher2', 'Login', '120.188.79.211', '2024-09-19', '23:18:05'),
(280, 'scardflasher2', 'Login', '114.5.111.219', '2024-09-20', '14:31:56'),
(281, 'scardflasher2', 'Login', '114.5.111.219', '2024-09-20', '15:24:44'),
(282, 'scardflasher2', 'Login', '114.5.110.147', '2024-09-20', '16:38:57'),
(283, 'scardflasher2', 'Login', '114.5.110.147', '2024-09-20', '16:40:07'),
(284, 'scardflasher2', 'Login', '114.5.103.179', '2024-09-21', '10:53:48'),
(285, 'scardflasher2', 'Login', '114.5.222.221', '2024-09-21', '13:19:58'),
(286, 'scardflasher2', 'Login', '114.5.104.156', '2024-09-24', '08:53:58'),
(287, 'scardflasher2', 'Login', '114.5.104.156', '2024-09-24', '09:05:20'),
(288, 'scardflasher2', 'Login', '114.5.111.239', '2024-09-27', '13:39:08'),
(289, 'scardflasher2', 'Login', '114.5.111.239', '2024-09-27', '13:39:35'),
(290, 'scardflasher2', 'Login', '120.188.74.136', '2024-10-11', '17:32:35'),
(291, 'scardflasher2', 'Login', '120.188.74.136', '2024-10-11', '17:34:42'),
(292, 'scardflasher2', 'Login', '120.188.74.136', '2024-10-11', '17:43:24'),
(293, 'scardflasher2', 'Login', '120.188.74.136', '2024-10-11', '18:05:38'),
(294, 'scardflasher2', 'Login', '120.188.74.136', '2024-10-11', '18:11:01'),
(295, 'scardflasher2', 'Login', '114.5.111.177', '2024-10-12', '10:41:45'),
(296, 'scardflasher2', 'Login', '114.5.111.163', '2024-10-13', '22:06:57'),
(297, 'scardflasher2', 'Login', '114.5.223.205', '2024-10-14', '09:32:40'),
(298, 'scardflasher2', 'Logout', '114.5.223.205', '2024-10-14', '10:06:41'),
(299, 'ghorieb', 'Login', '146.75.160.29', '2024-10-14', '10:15:25'),
(300, 'ghorieb', 'Logout', '2a04:4e41:f:e::9268:eeb', '2024-10-14', '10:17:45'),
(301, 'scardflasher2', 'Login', '114.5.223.205', '2024-10-14', '10:23:17'),
(302, 'ghorieb', 'Login', '2a04:4e41:f:e::73c0:ca83', '2024-10-14', '10:26:39'),
(303, 'ghorieb', 'Login', '146.75.160.29', '2024-10-14', '10:55:36'),
(304, 'scardflasher2', 'Login', '120.188.79.254', '2024-10-14', '14:49:04'),
(305, 'scardflasher2', 'Login', '120.188.79.254', '2024-10-14', '14:58:44'),
(306, 'ghorieb', 'Login', '146.75.160.29', '2024-10-15', '10:16:26'),
(307, 'ghorieb', 'Login', '2a04:4e41:f:e::6268:c7fc', '2024-10-16', '14:24:43'),
(308, 'ghorieb', 'Login', '2a09:bac3:39a0:1d05::2e4:8c', '2024-10-16', '14:42:39'),
(309, 'scardflasher2', 'Login', '120.188.75.155', '2024-10-19', '10:03:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `metode_depo`
--

CREATE TABLE `metode_depo` (
  `id` int(255) NOT NULL,
  `tipe` enum('Pulsa','Bank','EMoney','EPayment','ECurrency') NOT NULL,
  `provider` varchar(255) NOT NULL,
  `jalur` enum('Auto','Manual') NOT NULL,
  `nama` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `tujuan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `metode_depo`
--

INSERT INTO `metode_depo` (`id`, `tipe`, `provider`, `jalur`, `nama`, `rate`, `keterangan`, `tujuan`) VALUES
(2, 'EMoney', 'GOPAY', 'Auto', 'GOPAY Konfirmasi Otomatis', '1', 'ON', '081411105109'),
(3, 'EMoney', 'OVO', 'Auto', 'OVO Konfirmasi Otomatis', '1', 'ON', '081411105109'),
(4, 'EMoney', 'DANA', 'Auto', 'DANA Konfirmasi Otomatis', '1', 'ON', '081411105109'),
(6, 'Bank', 'BNI', 'Auto', 'BNI Konfirmasi Otomatis', '1', 'ON', '081411105109'),
(7, 'Bank', 'BRI', 'Auto', 'BRI Konfirmasi Otomatis', '1', 'ON', '081411105109'),
(8, 'Bank', 'BCA', 'Auto', 'BCA Konfirmasi Otomatis', '1', 'ON', 'GANTI'),
(9, 'Bank', 'BNI', 'Auto', 'BNI Konfirmasi Otomatis', '1', 'ON', 'GANTI');

-- --------------------------------------------------------

--
-- Struktur dari tabel `oke`
--

CREATE TABLE `oke` (
  `id` int(11) NOT NULL,
  `member_id` text NOT NULL,
  `password` text NOT NULL,
  `pin` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_digital`
--

CREATE TABLE `pembelian_digital` (
  `id` int(10) NOT NULL,
  `oid` varchar(50) NOT NULL,
  `provider_oid` varchar(50) NOT NULL,
  `user` varchar(50) NOT NULL,
  `layanan` varchar(100) NOT NULL,
  `harga` double NOT NULL,
  `profit` varchar(50) NOT NULL,
  `target` varchar(50) NOT NULL,
  `no_meter` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `status` enum('Pending','Processing','Error','Partial','Success') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'Website',
  `provider` varchar(100) NOT NULL,
  `refund` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_pulsa`
--

CREATE TABLE `pembelian_pulsa` (
  `id` int(10) NOT NULL,
  `oid` varchar(50) NOT NULL,
  `provider_oid` varchar(50) NOT NULL,
  `user` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `layanan` varchar(100) NOT NULL,
  `harga` double NOT NULL,
  `profit` varchar(50) NOT NULL,
  `target` varchar(50) NOT NULL,
  `no_meter` varchar(50) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `status` enum('Pending','Processing','Error','Partial','Success') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `place_from` varchar(50) NOT NULL DEFAULT 'Website',
  `provider` varchar(100) NOT NULL,
  `refund` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_sosmed`
--

CREATE TABLE `pembelian_sosmed` (
  `id` int(10) NOT NULL,
  `oid` varchar(50) NOT NULL,
  `provider_oid` varchar(50) NOT NULL,
  `user` varchar(100) NOT NULL,
  `layanan` varchar(100) NOT NULL,
  `target` text NOT NULL,
  `jumlah` int(10) NOT NULL,
  `remains` varchar(10) NOT NULL,
  `start_count` varchar(10) NOT NULL,
  `harga` double NOT NULL,
  `profit` double NOT NULL,
  `status` enum('Pending','Processing','In progress','Error','Partial','Success') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `provider` varchar(100) NOT NULL,
  `place_from` enum('Website','API') NOT NULL,
  `refund` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_tiket`
--

CREATE TABLE `pesan_tiket` (
  `id` int(10) NOT NULL,
  `id_tiket` int(10) NOT NULL,
  `pengirim` enum('Member','Admin') NOT NULL,
  `user` varchar(50) NOT NULL,
  `pesan` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `update_terakhir` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_tsel`
--

CREATE TABLE `pesan_tsel` (
  `id` int(11) NOT NULL,
  `isi` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `status` enum('UNREAD','READ') NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `provider`
--

CREATE TABLE `provider` (
  `id` int(10) NOT NULL,
  `code` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `api_key` varchar(100) NOT NULL,
  `api_id` varchar(50) NOT NULL,
  `secret_key` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data untuk tabel `provider`
--

INSERT INTO `provider` (`id`, `code`, `link`, `api_key`, `api_id`, `secret_key`) VALUES
(2, 'roamerimei', 'https://ceirgo.id/api/', 'XcsILKp9Y3vRPwfprUbPzmjNsBTSFAXh', '', ''),
(3, 'WAGATEWAY', 'https://www.scardwaget.my.id', 'N6dhg8C2S5RWT1FBERTCOB1JcwccVr', '628982765556', ''),
(5, 'MANUAL', '', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_transfer`
--

CREATE TABLE `riwayat_transfer` (
  `id` int(10) NOT NULL,
  `pengirim` varchar(50) NOT NULL,
  `penerima` varchar(50) NOT NULL,
  `jumlah` double NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_profit`
--

CREATE TABLE `setting_profit` (
  `id` int(11) NOT NULL,
  `kategori` enum('WEBSITE','API') NOT NULL,
  `tipe` enum('Sosial Media','PPOB','Digital') NOT NULL,
  `harga` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `setting_profit`
--

INSERT INTO `setting_profit` (`id`, `kategori`, `tipe`, `harga`) VALUES
(1, 'WEBSITE', 'Sosial Media', '1.2'),
(2, 'API', 'Sosial Media', '1.17'),
(3, 'WEBSITE', 'PPOB', '1.1'),
(4, 'API', 'PPOB', '1.08'),
(5, 'WEBSITE', 'Digital', '1.1'),
(6, 'API', 'Digital', '1.07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_web`
--

CREATE TABLE `setting_web` (
  `id` int(11) NOT NULL,
  `short_title` text NOT NULL,
  `title` text NOT NULL,
  `wa_notif` text NOT NULL,
  `deskripsi_web` text NOT NULL,
  `kontak_utama` text NOT NULL,
  `alamat` text NOT NULL,
  `url_alamat` text NOT NULL,
  `facebook` text NOT NULL,
  `url_facebook` text NOT NULL,
  `instagram` text NOT NULL,
  `url_instagram` text NOT NULL,
  `whatsapp` text NOT NULL,
  `url_whatsapp` text NOT NULL,
  `youtube` text NOT NULL,
  `url_youtube` text NOT NULL,
  `twitter` text NOT NULL,
  `url_twitter` text NOT NULL,
  `email` text NOT NULL,
  `url_email` text NOT NULL,
  `jam_kerja` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `setting_web`
--

INSERT INTO `setting_web` (`id`, `short_title`, `title`, `wa_notif`, `deskripsi_web`, `kontak_utama`, `alamat`, `url_alamat`, `facebook`, `url_facebook`, `instagram`, `url_instagram`, `whatsapp`, `url_whatsapp`, `youtube`, `url_youtube`, `twitter`, `url_twitter`, `email`, `url_email`, `jam_kerja`, `date`, `time`) VALUES
(1, 'gmj-roamer', 'gmjroamer - Panel imei Terbaik', '6281411105109', 'Distributor operator Imei H2H . Provider terbaik dan terlengkap.', ' +6281411105109', 'Indonesia', '', '', '', '', '', '+6281411105109', '', '', '', '', '', 'Help@gmjroamer', 'mailto:help@gmjroamer', '10:00 - 21:00 WIB', '2019-01-03', '16:06:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tiket`
--

CREATE TABLE `tiket` (
  `id` int(10) NOT NULL,
  `user` varchar(50) NOT NULL,
  `subjek` varchar(250) NOT NULL,
  `pesan` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `update_terakhir` datetime NOT NULL,
  `status` enum('Pending','Responded','Waiting','Closed') NOT NULL,
  `this_user` int(1) NOT NULL,
  `this_admin` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `saldo` int(10) NOT NULL,
  `pemakaian_saldo` double NOT NULL,
  `level` enum('Member','Agen','Admin','Developers','Reseller') NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `api_key` varchar(100) NOT NULL,
  `uplink` varchar(100) NOT NULL,
  `terdaftar` datetime NOT NULL,
  `update_nama` int(1) NOT NULL,
  `random_kode` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `no_hp`, `username`, `password`, `saldo`, `pemakaian_saldo`, `level`, `status`, `api_key`, `uplink`, `terdaftar`, `update_nama`, `random_kode`) VALUES
(8, 'Demo2', 'sugiartosteve0@gmail.com', '087761891018', 'scardflasher2', '$2y$10$CWULYNqVqTaF1JqyVTTaIuz1Jog58wp4cFAWfH34WkQfcTYvy3xAq', 100000, 1347944, 'Developers', 'Aktif', 'S6hFyk61751vS3UmFJSkVEQs8cTF7elj', 'Pendaftaran Gratis', '2024-08-13 15:55:24', 0, ''),
(11, 'Muhamad Syarif H', 'muhamadsyarifh@icloud.com', '081335812051', 'ghorieb', '$2y$10$cvnNJi/IURY.fKmTBVSFD.hwQDkAT56FRmX.t7sUrn8wt1XvC5iDa', 99999999, 0, 'Developers', 'Aktif', '8w01wiHp7GCxwbkN9a3BzngVEk8eLajc', 'Pendaftaran Gratis', '2024-10-14 10:15:02', 0, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_token`
--

CREATE TABLE `users_token` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `tokenuser` varchar(80) NOT NULL,
  `timemodified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `users_token`
--

INSERT INTO `users_token` (`id`, `username`, `tokenuser`, `timemodified`) VALUES
(14, 'admin', 'lCERIjAer5', '2024-08-24 19:53:13'),
(15, 'scardflasher', 'UD555sIrMk', '2024-07-07 15:01:52'),
(18, 'maranzano', 'QIpdlYDcIG', '2024-08-26 10:29:30'),
(25, 'scardflasher2', 'UMwgnHH91t', '2024-10-19 03:03:05'),
(26, 'ghorieb', 'zXCIcQ2iuY', '2024-10-16 07:42:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `voucher`
--

CREATE TABLE `voucher` (
  `id` int(10) NOT NULL,
  `voucher` varchar(50) NOT NULL,
  `saldo` varchar(250) NOT NULL,
  `status` enum('active','sudah di redeem') NOT NULL,
  `user` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wa`
--

CREATE TABLE `wa` (
  `id` int(11) NOT NULL,
  `data_id` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `deposit_bank`
--
ALTER TABLE `deposit_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `deposit_emoney`
--
ALTER TABLE `deposit_emoney`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `deposit_epayment`
--
ALTER TABLE `deposit_epayment`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `deposit_tsel`
--
ALTER TABLE `deposit_tsel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `deposit_voucher`
--
ALTER TABLE `deposit_voucher`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `edit_sn`
--
ALTER TABLE `edit_sn`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `halaman`
--
ALTER TABLE `halaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `harga_pendaftaran`
--
ALTER TABLE `harga_pendaftaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `history_saldo`
--
ALTER TABLE `history_saldo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_layanan`
--
ALTER TABLE `kategori_layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_layanan2`
--
ALTER TABLE `kategori_layanan2`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_layanan3`
--
ALTER TABLE `kategori_layanan3`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan_digital`
--
ALTER TABLE `layanan_digital`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan_pulsa`
--
ALTER TABLE `layanan_pulsa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan_sosmed`
--
ALTER TABLE `layanan_sosmed`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan_sosmed2`
--
ALTER TABLE `layanan_sosmed2`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan_sosmed3`
--
ALTER TABLE `layanan_sosmed3`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `metode_depo`
--
ALTER TABLE `metode_depo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `oke`
--
ALTER TABLE `oke`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian_digital`
--
ALTER TABLE `pembelian_digital`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian_pulsa`
--
ALTER TABLE `pembelian_pulsa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian_sosmed`
--
ALTER TABLE `pembelian_sosmed`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan_tiket`
--
ALTER TABLE `pesan_tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan_tsel`
--
ALTER TABLE `pesan_tsel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `riwayat_transfer`
--
ALTER TABLE `riwayat_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_profit`
--
ALTER TABLE `setting_profit`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_web`
--
ALTER TABLE `setting_web`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users_token`
--
ALTER TABLE `users_token`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wa`
--
ALTER TABLE `wa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `deposit_bank`
--
ALTER TABLE `deposit_bank`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `deposit_emoney`
--
ALTER TABLE `deposit_emoney`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `deposit_epayment`
--
ALTER TABLE `deposit_epayment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `deposit_tsel`
--
ALTER TABLE `deposit_tsel`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `deposit_voucher`
--
ALTER TABLE `deposit_voucher`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `edit_sn`
--
ALTER TABLE `edit_sn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `halaman`
--
ALTER TABLE `halaman`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `harga_pendaftaran`
--
ALTER TABLE `harga_pendaftaran`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `history_saldo`
--
ALTER TABLE `history_saldo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT untuk tabel `kategori_layanan`
--
ALTER TABLE `kategori_layanan`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kategori_layanan2`
--
ALTER TABLE `kategori_layanan2`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_layanan3`
--
ALTER TABLE `kategori_layanan3`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan_digital`
--
ALTER TABLE `layanan_digital`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `layanan_pulsa`
--
ALTER TABLE `layanan_pulsa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan_sosmed`
--
ALTER TABLE `layanan_sosmed`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan_sosmed2`
--
ALTER TABLE `layanan_sosmed2`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan_sosmed3`
--
ALTER TABLE `layanan_sosmed3`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log`
--
ALTER TABLE `log`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT untuk tabel `metode_depo`
--
ALTER TABLE `metode_depo`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `oke`
--
ALTER TABLE `oke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembelian_digital`
--
ALTER TABLE `pembelian_digital`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `pembelian_pulsa`
--
ALTER TABLE `pembelian_pulsa`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembelian_sosmed`
--
ALTER TABLE `pembelian_sosmed`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesan_tiket`
--
ALTER TABLE `pesan_tiket`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesan_tsel`
--
ALTER TABLE `pesan_tsel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `riwayat_transfer`
--
ALTER TABLE `riwayat_transfer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `setting_profit`
--
ALTER TABLE `setting_profit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `setting_web`
--
ALTER TABLE `setting_web`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users_token`
--
ALTER TABLE `users_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wa`
--
ALTER TABLE `wa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
