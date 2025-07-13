-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 13 Jul 2025 pada 12.49
-- Versi server: 8.0.42-0ubuntu0.24.04.1
-- Versi PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Jualin`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `accounts`
--

CREATE TABLE `accounts` (
  `AccountId` char(36) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `RoleId` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `accounts`
--

INSERT INTO `accounts` (`AccountId`, `Email`, `Password`, `FullName`, `PhoneNumber`, `BirthDate`, `RoleId`, `created_at`, `updated_at`, `deleted_at`) VALUES
('fb6c5b17-ca63-4c60-8780-a559a92a9569', 'esi.mikisia567@gmail.com', '$2y$10$u2kSfFZVW6T.9O78.eu7Y.tbDRE/LfS7EjTJlwIFkn4NyAe0BG9T6', 'Mikisia Esi', '085754435164', '2001-10-02', 2, '2025-06-12 15:32:02', '2025-06-12 15:32:02', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `PaymentId` char(36) NOT NULL,
  `SaleId` char(36) NOT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `PaymentDate` datetime DEFAULT NULL,
  `Status` enum('success','pending','failed') DEFAULT 'pending',
  `Amount` int DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `AccountId` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `image` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `AccountId`, `name`, `description`, `price`, `stock`, `status`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'iPhone 15 Pro', 'iPhone 15 Pro adalah generasi terbaru dari Apple yang hadir dengan bodi titanium yang lebih ringan dan tahan lama. Mengusung chipset A17 Pro, iPhone ini memberikan performa grafis luar biasa untuk gaming dan aplikasi berat, serta efisiensi tinggi untuk penggunaan harian. Sistem kamera Pro 48MP dengan Photonic Engine memungkinkan pengambilan foto dengan detail tinggi dan akurasi warna yang sempurna. Layar Super Retina XDR 6.1 inci mendukung ProMotion 120Hz. Fitur unggulan lainnya meliputi USB-C, Action Button kustom, Face ID, dan dukungan iOS terbaru.', 21499000, 8, 'active', '1750524672_597af590ec020310d0e9.jpg', '2025-06-13 08:47:09', '2025-06-21 10:07:23', NULL),
(3, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'Samsung Galaxy S23 Ultra', 'Samsung Galaxy S23 Ultra adalah smartphone flagship terbaru dari Samsung yang dirancang untuk pengguna yang menginginkan performa terbaik dan kemampuan fotografi profesional dalam satu perangkat. Ditenagai oleh prosesor Snapdragon 8 Gen 2 for Galaxy, ponsel ini menghadirkan kecepatan tinggi dan efisiensi daya. Kamera utama 200MP dilengkapi dengan fitur Nightography, OIS, dan kemampuan zoom hingga 100x, memungkinkan pengambilan gambar yang tajam dalam segala kondisi cahaya. Layar Dynamic AMOLED 2X berukuran 6,8 inci memberikan visual yang tajam dan jernih dengan refresh rate 120Hz. Dilengkapi baterai 5.000mAh dan fast charging 45W.', 19999000, 12, 'active', '1750524535_c30007f15d5299ffef93.jpg', '2025-06-21 09:48:55', '2025-06-21 10:37:38', NULL),
(4, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'Xiaomi 14 Ultra', 'Xiaomi 14 Ultra adalah flagship yang menekankan pada fotografi profesional. Berkolaborasi dengan Leica, kamera belakang quad 50MP-nya memiliki lensa variabel dan dukungan RAW 14-bit. Prosesor Snapdragon 8 Gen 3 dan RAM LPDDR5X menjamin kecepatan tinggi untuk semua kebutuhan. Layar AMOLED 6.73 inci dengan resolusi WQHD+ dan refresh rate 120Hz membuat tampilan visual sangat tajam. Daya tahan diperkuat dengan baterai 5300mAh dan pengisian cepat 90W.', 16999000, 12, 'active', '1750526390_244375d4fe44d40bdbfd.jpg', '2025-06-21 10:19:50', '2025-06-21 10:37:38', NULL),
(5, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'OPPO Find X7 Ultra', 'OPPO Find X7 Ultra hadir dengan sistem dual-periscope pertama di dunia, memungkinkan zoom optik hingga 14x tanpa kehilangan detail. Lensa Hasselblad memberikan hasil warna yang lebih alami dan sinematik. Ditenagai oleh Snapdragon 8 Gen 2 dan ColorOS terbaru, perangkat ini dirancang untuk kecepatan dan efisiensi energi. Layarnya berukuran 6.82 inci AMOLED dengan tingkat kecerahan tinggi yang nyaman digunakan di bawah sinar matahari. Daya tahan dijamin oleh baterai 5000mAh dengan pengisian cepat SuperVOOC 100W.', 15499000, 10, 'active', '1750526547_78a0a32d745b231be6ad.webp', '2025-06-21 10:22:27', '2025-06-21 10:37:38', NULL),
(6, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'Realme GT 6', 'realme GT 6 adalah ponsel performa tinggi dengan harga terjangkau, ideal untuk gamer dan pengguna multitasking. Menggunakan prosesor Snapdragon 8s Gen 3 dan RAM 12GB, performanya sangat stabil bahkan saat menjalankan aplikasi berat. Layar AMOLED 6.78 inci dengan refresh rate 144Hz menghadirkan visual super mulus. Kamera utama 50MP Sony LYT-808 dengan OIS memberikan hasil jepretan tajam dan stabil. Baterai 5500mAh dilengkapi pengisian SUPERVOOC 120W, mengisi penuh dalam waktu kurang dari 30 menit.', 8499000, 20, 'active', '1750526623_f0b2798fc4198e9d3dd9.jpg', '2025-06-21 10:23:43', '2025-06-21 10:37:38', NULL),
(7, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'ASUS ROG Phone 8 Pro', 'ASUS ROG Phone 8 Pro dirancang khusus untuk para gamer mobile yang menginginkan performa tanpa kompromi. Menggunakan chipset Snapdragon 8 Gen 3 dan RAM LPDDR5X hingga 24GB, ponsel ini mampu menjalankan game berat dengan frame rate tinggi tanpa lag. Layar AMOLED 6.78 inci dengan refresh rate 165Hz memberikan respons sentuh super cepat, sangat cocok untuk e-sports. Dilengkapi dengan sistem pendingin GameCool 8 dan fitur AirTrigger untuk kontrol tambahan saat bermain. Baterai 5500mAh dan pengisian cepat 65W membuat sesi bermain tak terganggu.', 14999000, 6, 'inactive', '1750526702_ffe7101ae839f226701d.jpeg', '2025-06-21 10:25:02', '2025-06-21 10:37:38', NULL),
(8, 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'Vivo X100 Pro', 'vivo X100 Pro adalah smartphone premium yang menonjol di sektor fotografi dan performa. Ditenagai oleh MediaTek Dimensity 9300 dan AI Image Processing terbaru, X100 Pro mampu menangkap gambar dengan detail menakjubkan melalui lensa Zeiss APO Floating Telephoto. Kamera utama 50MP memiliki sensor 1 inci yang besar, ideal untuk low-light photography. Teknologi V2 Imaging Chip meningkatkan hasil gambar secara real-time. Selain itu, layar LTPO AMOLED 120Hz menghadirkan visual yang halus, dan baterai 5400mAh dengan 100W FlashCharge mendukung gaya hidup aktif tanpa harus sering mengisi daya.', 13499000, 9, 'active', '1750527013_6f846f8f5322449b09bf.jpeg', '2025-06-21 10:30:13', '2025-06-21 10:37:38', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `SaleId` char(36) NOT NULL,
  `StoreId` char(36) NOT NULL,
  `ProductId` int NOT NULL,
  `PaymentId` char(36) NOT NULL,
  `BuyerPhone` varchar(20) DEFAULT NULL,
  `SendAddress` text NOT NULL,
  `Quantity` int DEFAULT '1',
  `TotalPrice` int DEFAULT NULL,
  `PaymentStatus` enum('pending','paid','failed') DEFAULT 'pending',
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DeletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stores`
--

CREATE TABLE `stores` (
  `StoreId` char(36) NOT NULL,
  `AccountId` char(36) NOT NULL,
  `StoreName` varchar(255) NOT NULL,
  `StoreCategory` varchar(255) DEFAULT NULL,
  `StoreDescription` text,
  `StoreAddress` text,
  `StoreProvince` varchar(100) DEFAULT NULL,
  `StoreCity` varchar(100) DEFAULT NULL,
  `StoreZipCode` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `stores`
--

INSERT INTO `stores` (`StoreId`, `AccountId`, `StoreName`, `StoreCategory`, `StoreDescription`, `StoreAddress`, `StoreProvince`, `StoreCity`, `StoreZipCode`, `created_at`, `updated_at`, `deleted_at`) VALUES
('13fbd769-1996-4bf5-91ad-63c4877f360f', 'fb6c5b17-ca63-4c60-8780-a559a92a9569', 'GadgedGenius', 'elektronik', 'Toko Service Handphone', 'Jalan raya sungai nipah', '61', '6171', '78351', '2025-06-12 15:32:02', '2025-06-12 15:32:02', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `token`
--

CREATE TABLE `token` (
  `StoreId` char(36) NOT NULL,
  `token` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`AccountId`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentId`),
  ADD KEY `fk_payments_sales` (`SaleId`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_account` (`AccountId`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SaleId`),
  ADD KEY `StoreId` (`StoreId`),
  ADD KEY `fk_sales_product` (`ProductId`),
  ADD KEY `fk_sales_payment` (`PaymentId`);

--
-- Indeks untuk tabel `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`StoreId`),
  ADD KEY `fk_stores_account` (`AccountId`);

--
-- Indeks untuk tabel `token`
--
ALTER TABLE `token`
  ADD KEY `fk_token_store` (`StoreId`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_sales` FOREIGN KEY (`SaleId`) REFERENCES `sales` (`SaleId`),
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`SaleId`) REFERENCES `sales` (`SaleId`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_account` FOREIGN KEY (`AccountId`) REFERENCES `accounts` (`AccountId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_payment` FOREIGN KEY (`PaymentId`) REFERENCES `payments` (`PaymentId`),
  ADD CONSTRAINT `fk_sales_product` FOREIGN KEY (`ProductId`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`StoreId`) REFERENCES `stores` (`StoreId`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `fk_stores_account` FOREIGN KEY (`AccountId`) REFERENCES `accounts` (`AccountId`);

--
-- Ketidakleluasaan untuk tabel `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_token_store` FOREIGN KEY (`StoreId`) REFERENCES `stores` (`StoreId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
