# Dokumentasi Aplikasi Pengaduan Sarana Sekolah (SIPESKU)
**UKK RPL 2025/2026**

---

## 1. Arsitektur Sistem
Aplikasi ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** sederhana dengan PHP Native.

- **Config**: Konfigurasi database dan konstanta global.
- **Helpers**: Fungsi bantuan untuk autentikasi, manajemen role, dan keamanan.
- **Models**: Logika bisnis dan interaksi database.
- **Controllers**: Menghubungkan model dengan view dan menangani request pengguna.
- **Views**: Antarmuka pengguna (Frontend).
- **Assets**: File statis (CSS, JS, Gambar).

---

## 2. Struktur Database (ERD) -> Text Representation

### Tabel: roles
- **id_role** (PK, INT, AI)
- `nama_role` (VARCHAR)
- `deskripsi_role` (TEXT)

### Tabel: admin
- **id_admin** (PK, INT, AI)
- `username` (VARCHAR)
- `password` (VARCHAR, MD5)

### Tabel: siswa
- **nisn** (PK, CHAR)
- `nama` (VARCHAR)
- `kelas` (VARCHAR)
- `password` (VARCHAR, MD5)
- `id_role` (FK -> roles.id_role)

### Tabel: kategori
- **id_kategori** (PK, INT, AI)
- `nama_kategori` (VARCHAR)

### Tabel: input_aspirasi
- **id_pelaporan** (PK, INT, AI)
- `nisn` (FK -> siswa.nisn)
- `id_kategori` (FK -> kategori.id_kategori)
- `isi_aspirasi` (TEXT)
- `tgl_input` (TIMESTAMP)

### Tabel: aspirasi
- **id_aspirasi** (PK, INT, AI)
- `id_pelaporan` (FK -> input_aspirasi.id_pelaporan)
- `feedback` (TEXT)
- `status` (ENUM: menunggu, proses, selesai)
- `tgl_feedback` (TIMESTAMP)

**Relasi:**
- `siswa` many-to-one `roles`
- `input_aspirasi` many-to-one `siswa`
- `input_aspirasi` many-to-one `kategori`
- `aspirasi` one-to-one `input_aspirasi` (Secara teknis 1 aspirasi menangani 1 pelaporan, bisa dikembangkan)

---

## 3. Alur Sistem (Flowchart)

### Alur Siswa:
1. Login (NISN + Password).
2. Dashboard -> Pilih "Kirim Aspirasi".
3. Isi Form (Kategori, Isi Laporan) -> Submit.
4. Sistem menyimpan data ke `input_aspirasi` dan membuat record `aspirasi` dengan status 'menunggu'.
5. Siswa dialihkan ke halaman "Riwayat".

### Alur Admin:
1. Login (Username + Password).
2. Dashboard -> Lihat statistik.
3. Menu "Aspirasi" -> Lihat daftar laporan masuk.
4. Pilih Detail -> Ubah status (Proses/Selesai) & Beri Feedback.
5. Simpan -> Siswa dapat melihat update di akun mereka.

---

## 4. Dokumentasi Fungsi & Prosedur Penting

### `config/database.php`
- `base_url($path)`: Helper untuk menghasilkan URL absolut yang aman.

### `helpers/auth_helper.php`
- `check_login()`: Memastikan user sudah login sebelum mengakses halaman terlindungi.
- `check_role($allowed)`: Membatasi akses berdasarkan array role yang diizinkan.
- `h($string)`: Wrapper `htmlspecialchars` untuk mencegah XSS.

### `models/Aspirasi.php`
- `create($nisn, $cat, $isi)`: Menjalankan transaksi DB untuk insert ke tabel `input_aspirasi` dan `aspirasi` sekaligus.
- `updateStatus($id, $stats, $fb)`: Mengupdate status laporan dan mencatat waktu feedback.

---

## 5. Keamanan & Optimasi
- **XSS Protection**: Semua output user dibungkus fungsi `h()`.
- **SQL Injection**: Semua query database menggunakan **Prepared Statements (PDO)**.
- **Password Hashing**: Menggunakan MD5 (sesuai instruksi soal UKK).
- **Indexing**: Tabel database memiliki index pada kolom yang sering dicari/difilter (status, nisn, id_role).
- **Assets**: CSS diminimalkan dan menggunakan variabel untuk konsistensi tema.

---

## 6. Cara Instalasi
1. Copy folder `ukk_wiene2` ke `htdocs`.
2. Buka `localhost/phpmyadmin`.
3. Buat database baru bernama `ukk_wiene2`.
4. Import file `config/database.sql` ke database tersebut.
5. Akses `http://localhost/ukk_wiene2` di browser.

**Akun Demo:**
- **Admin**: user: `admin`, pass: `user123`
- **Siswa (Buat manual di DB atau via Admin jika fitur aktif)**:
    - Insert via SQL: `INSERT INTO siswa VALUES ('12345', 'Budi', 'XII RPL 1', MD5('siswa123'), 2, NOW());`
    - Login: `12345` / `siswa123`
