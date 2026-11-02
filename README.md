# SKENA – Platform Marketplace Jasa Kreatif

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

Aplikasi web marketplace jasa kreatif yang menghubungkan penyedia jasa dengan klien.  
Dibangun dengan **Laravel 12**, **MySQL 8**, **Tailwind CSS**, dan arsitektur **MVC**.

---

## 📋 Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Pembagian PIC](#pembagian-pic)
- [Cara Install](#cara-install)
- [Struktur Folder](#struktur-folder)
- [Kontribusi](#kontribusi)

---

## ✨ Fitur

| Modul | PBI | PIC |
|---|---|---|
| Registrasi & Login | FR-01, FR-02 | Tim |
| Dashboard Admin | FR-03, FR-04 | Tim |
| Manajemen User & Role | FR-05, FR-06 | Tim |
| Profil & Pengalaman | PBI-01 | Earliyanza |
| Manajemen Skill | PBI-02 | Earliyanza |
| Log Admin & Moderasi Konten | PBI-03 | Earliyanza |
| Manajemen Jasa | PBI-04 | Anindya |
| Kategori Jasa | PBI-05 | Anindya |
| Portofolio | PBI-06 | Anindya |
| Wishlist / Favorit | PBI-07 | Rizal |
| Pencarian Tersimpan | PBI-08 | Rizal |
| Voucher | PBI-09 | Rizal |
| Penelusuran & Detail Jasa | FR-25, FR-26 | Rizal |
| Request Jasa | PBI-10 | Mahendra |
| Pesanan & Negosiasi | PBI-11, PBI-12 | Mahendra |
| Pembayaran | PBI-13 | Firzatullah |
| Status Pengerjaan | PBI-14 | Firzatullah |
| Chat / Pesan | PBI-15 | Firzatullah |
| Review & Rating | PBI-16 | Aprilia |
| Notifikasi | PBI-17 | Aprilia |
| Laporan | PBI-18 | Aprilia |

---

## 🛠️ Teknologi

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL 8
- **Frontend**: Blade + Tailwind CSS + Alpine.js
- **Auth**: Laravel Breeze / Fortify
- **Testing**: PHPUnit + Laravel Feature Test

---

## 👥 Pembagian PIC

| Anggota | PBI |
|---|---|
| **Earliyanza** | PBI-01 Profil, PBI-02 Skill, PBI-03 Log Admin |
| **Anindya** | PBI-04 Jasa, PBI-05 Kategori, PBI-06 Portfolio |
| **Rizal** | PBI-07 Wishlist, PBI-08 Pencarian Tersimpan, PBI-09 Voucher, FR-25, FR-26 |
| **Mahendra** | PBI-10 Request, PBI-11 Pesanan, PBI-12 Negosiasi |
| **Firzatullah** | PBI-13 Pembayaran, PBI-14 Status Pengerjaan, PBI-15 Chat |
| **Aprilia** | PBI-16 Review, PBI-17 Notifikasi, PBI-18 Laporan |
| **Seluruh Tim** | Fondasi akun (FR-01–FR-06), integrasi, pengujian, UAT |

---

## 🚀 Cara Install

### Prasyarat

- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- MySQL 8

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/<org>/skena.git
cd skena

# 2. Install dependensi
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Atur koneksi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=skena
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrasi & seed database
php artisan migrate --seed

# 6. Buat storage link
php artisan storage:link

# 7. Build aset frontend
npm run dev

# 8. Jalankan server lokal
php artisan serve
```

Atau gunakan script otomatis:

```bash
bash setup.sh
```

---

## 📂 Struktur Folder

Lihat [`struktur-skena.md`](struktur-skena.md) untuk dokumentasi lengkap struktur folder.

---

## 🤝 Kontribusi

1. Fork repositori ini
2. Buat branch fitur: `git checkout -b feature/PBI-XX-nama-fitur`
3. Commit perubahan: `git commit -m "feat(PBI-XX): deskripsi singkat"`
4. Push ke branch: `git push origin feature/PBI-XX-nama-fitur`
5. Buat Pull Request ke branch `develop`

> **Konvensi commit**: `feat`, `fix`, `docs`, `test`, `refactor` + scope PBI

---

## 📄 Lisensi

MIT License — lihat [LICENSE](LICENSE).
