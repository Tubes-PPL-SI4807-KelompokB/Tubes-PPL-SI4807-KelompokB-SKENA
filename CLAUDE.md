# CLAUDE.md — SKENA (Skill Exchange Network)

File ini adalah instruksi kerja untuk Claude di repository SKENA. Claude bekerja sebagai **satu tim pengembangan virtual** yang terdiri dari lima peran: Project Manager, UI/UX Designer, Backend Developer, Frontend Developer, dan GitHub Manager. Baca seluruh file ini sebelum mengerjakan tugas apa pun.

---

## 1. Ringkasan Project

- **Nama produk:** SKENA — Skill Exchange Network
- **Jenis:** Aplikasi web responsif, marketplace jasa kecil antar mahasiswa (tema SDG 8: Decent Work and Economic Growth)
- **Contoh jasa:** desain PPT, foto produk, bantuan Excel, edit video, setup website, tutor SQL
- **Aktor:**
  - **Penyedia Jasa** — mahasiswa terverifikasi yang menawarkan skill sebagai jasa
  - **Pencari Jasa** — mahasiswa terverifikasi yang membutuhkan jasa (satu akun bisa berperan sebagai penyedia sekaligus pencari)
  - **Admin** — pemilik platform, akses ke seluruh sistem
- **Batasan pengembangan (jangan dilanggar):**
  - Pengguna hanya mahasiswa (verifikasi email kampus / NIM)
  - Pembayaran **disimulasikan** (unggah bukti + konfirmasi manual), TIDAK ada integrasi payment gateway
  - Web responsif saja, bukan aplikasi mobile native
  - Chat hanya pesan teks (tanpa voice/video call)

## 2. Tech Stack

- **Backend:** PHP 8.x + Laravel 12 (arsitektur MVC bawaan Laravel)
- **Frontend:** Blade + Tailwind CSS (Bootstrap hanya jika tim memutuskan) + JavaScript
- **Database:** MySQL 8, akses via Eloquent ORM
- **Web server:** Apache/Nginx
- **Storage:** Laravel Storage untuk portofolio dan bukti bayar
- **Tools pendukung:** Git/GitHub, VS Code, Figma, Draw.io, Postman

Alur request: `Browser → (HTTPS) → Routes + Middleware (auth & role) → Controller → Model (Eloquent) → MySQL → View (Blade)`

## 3. Kebutuhan Non-Fungsional (wajib dipenuhi setiap fitur)

- **NFR-01 Kinerja:** halaman utama ≤ 3 detik di jaringan 4G; target ±200 pengguna bersamaan → hindari N+1 query (gunakan eager loading), paginasi untuk semua list
- **NFR-02 Keamanan:** password bcrypt; proteksi SQL injection, XSS, CSRF; otorisasi berbasis peran; HTTPS
- **NFR-03 Keandalan:** ketersediaan ≥ 95%, backup database berkala
- **NFR-04 Skalabilitas:** arsitektur modular
- **NFR-05 Usability:** responsif desktop & mobile, mudah dipakai tanpa pelatihan

---

## 4. Tim Virtual & Peran

Saat menerima tugas, Claude **menentukan peran mana yang aktif** dan menuliskannya di awal respons, misalnya: `[PM] → [UI/UX] → [Backend] → [Frontend] → [GitHub Manager]`. Satu tugas boleh melibatkan beberapa peran secara berurutan.

### 4.1 🧭 Project Manager (PM)

**Tanggung jawab:**
- Memecah permintaan menjadi task kecil yang mengacu ke PBI / FR (lihat Bagian 5)
- Memastikan task sesuai Sprint yang berjalan dan tidak keluar dari scope proposal
- Menulis acceptance criteria untuk setiap task sebelum kode ditulis
- Mengidentifikasi dependensi antar PBI dan risiko (mis. PBI yang butuh tabel milik PBI lain)
- Membuat ringkasan progres: apa yang selesai, apa yang tertunda, blocker

**Aturan:**
- Jika permintaan di luar scope proposal (mis. payment gateway asli, video call), PM **wajib menandai** sebagai out-of-scope dan bertanya ke user sebelum lanjut
- Selalu cek PIC PBI; jangan mengubah modul milik anggota lain tanpa menyebutkannya
- Format output task:
  ```
  Task: <judul>
  PBI/FR: <PBI-xx / FR-xx>
  PIC: <nama>
  Sprint: <1/2>
  Acceptance Criteria:
  - [ ] ...
  Dependensi: <...>
  ```

### 4.2 🎨 UI/UX Designer

**Tanggung jawab:**
- Merancang alur halaman (user flow) dan struktur layout sebelum frontend dikerjakan
- Menjaga konsistensi design system (warna, tipografi, spacing, komponen)
- Menyusun state lengkap untuk tiap halaman: default, loading, empty state, error, sukses
- Memastikan aksesibilitas dasar: kontras warna, label pada setiap input, fokus keyboard, ukuran tap target di mobile

**Aturan:**
- Mockup resmi dibuat di Claude Design: **"Mockup Profil dan Skill SKENA"** — https://claude.ai/artifact/MC5MMuYvLHmkhBppK5imb3 — berisi 3 halaman: Kelola Profil (PBI-01), Kelola Skill (PBI-02), Kelola Portfolio (PBI-06). Halaman lain yang belum punya mockup **wajib mengikuti design system di bawah ini** supaya tampilannya seragam.
- Mobile-first, breakpoint Tailwind standar (`sm`, `md`, `lg`)
- Label tombol berbentuk kata kerja yang jelas ("Ajukan Request", "Konfirmasi Pembayaran"), hindari "OK" / "Submit"
- Bahasa antarmuka: **Bahasa Indonesia**
- Pesan error tidak menyalahkan pengguna dan selalu memberi langkah perbaikan
- Aksi destruktif (hapus, batalkan pesanan) wajib memakai konfirmasi yang menyebut nama item

**Design system (diambil dari mockup Claude Design):**

*Font*
- `Plus Jakarta Sans` (Google Fonts, weight 400/500/600/700), fallback `system-ui, sans-serif`
- Ukuran dasar 15px, line-height 1.5
- H1 halaman 28px bold · judul kartu (H2) 18px bold · sub-judul (H3) 15–16px bold · label input 13px semibold · teks bantu 13–14px

*Warna*
- Primary (tombol utama, toggle aktif, logo): `#3B4BDB`
- Primary dark (link, teks aktif): `#2F3DB8` · hover link `#1F2A8A`
- Primary soft (menu aktif, chip kategori): `#EEF0FF` · avatar bg `#DDE1FF` · `#E8EBFF`
- Teks utama: `#172033` · teks sekunder/nav: `#3A4358` · teks bantu: `#5B6478`
- Border input & tombol sekunder: `#8A93A8` · border kartu/divider: `#E3E6EF` · track/progress kosong: `#D5D9E8`
- Background halaman: `#F4F6FB` · kartu: `#FFFFFF` · panel form inline: `#F7F8FD`
- Danger (hapus, aksi destruktif): `#B42318`
- Badge "Unggulan": bg `#FFF4D6`, teks `#8A5A00`
- Warna placeholder/thumbnail portfolio: hijau `#E3F4EC`/`#0B6B47`, oranye `#FFF1E0`/`#9A4A00`, ungu `#F1E8FA`/`#6A2C99`

*Bentuk & ukuran*
- Radius: kartu 16px · panel form 12px · tombol/input/menu 10px · avatar & toggle bulat penuh
- Kartu: background putih + border 1px `#E3E6EF`, **tanpa shadow**, padding 24px
- Tinggi tombol & input: 44px (tap target), padding tombol 0 18–20px
- Jarak: grid 4/8px — gap antar kartu 24px, antar field 16px, label ke input 6px; padding halaman 32px; konten max-width 1280px

*Komponen*
- Tombol primary: bg `#3B4BDB`, teks putih, semibold
- Tombol sekunder: bg putih, border `#8A93A8`, teks `#172033`
- Tombol teks (Edit/Hapus di list): tanpa border, ikon 16px + label; Edit `#2F3DB8`, Hapus `#B42318`
- Toggle switch (status ketersediaan): 48×28px, aktif `#3B4BDB`, pakai `role="switch"` + `aria-checked`
- Chip kategori: bg `#EEF0FF`, teks `#2F3DB8`
- Indikator tingkat kemahiran skill: Pemula / Menengah / Mahir, dengan `role="img"` + `aria-label`
- Ikon: SVG garis (stroke 2, round cap) ukuran 16px, gaya seperti Lucide

*Layout halaman akun (pola dari mockup)*
- Header putih: logo "S" kotak biru 32px + teks SKENA · nav utama (Katalog, Pesanan, Chat, Favorit) · nama + avatar inisial di kanan
- Sidebar kiri 220px: Profil, Skill, Jasa saya, Portfolio, Pesanan masuk (item aktif bg `#EEF0FF` + `aria-current="page"`)
- Area utama: judul H1 + deskripsi singkat + tombol aksi di kanan → kartu-kartu konten
- Pola CRUD: daftar item dengan tombol Edit/Hapus per baris, lalu form "Tambah ... baru" di panel `#F7F8FD` di bawah daftar
- Layout pakai flex-wrap sehingga otomatis turun ke satu kolom di layar kecil

*Implementasi ke Tailwind*
- Daftarkan warna di atas sebagai custom color di `tailwind.config.js` (mis. `primary`, `primary-dark`, `primary-soft`, `ink`, `ink-2`, `muted`, `line`, `surface`, `danger`) dan font di `fontFamily.sans`
- Jangan menulis inline style seperti di file mockup; ubah menjadi class Tailwind + komponen Blade (`x-button`, `x-input`, `x-card`, `x-chip`, `x-toggle`, `x-badge`)

*Catatan:* judul board di mockup memakai kode FR-02 / FR-03 / FR-06, sedangkan di proposal halaman ini adalah FR-07 / FR-08 / FR-12 (PBI-01 / 02 / 06). Gunakan penomoran **proposal** sebagai acuan.

### 4.3 ⚙️ Backend Developer

**Tanggung jawab:**
- Migration, Model, Seeder, Factory, Controller, Form Request, Policy, Route
- Validasi input di Form Request, otorisasi di Policy / Gate / middleware role
- Logika bisnis alur transaksi (request → pesanan → negosiasi → pembayaran → status pengerjaan → review)
- Feature test untuk setiap CRUD

**Aturan arsitektur (dari proposal, wajib):**
- **Satu tabel dimiliki oleh satu PBI.** Jangan menulis ke tabel milik PBI lain.
- **Hanya pembuatan notifikasi otomatis yang boleh menulis lintas tabel** (ke tabel `notifications`) — gunakan Event + Listener atau Observer
- **Setiap PBI wajib punya seeder sendiri** sehingga bisa diuji tanpa menunggu PBI lain
- Akun yang sudah terkait pesanan/transaksi **tidak dihapus permanen**, hanya dinonaktifkan (soft delete / kolom status)
- Setiap aksi admin wajib tercatat otomatis ke `admin_logs`
- Rating skala 1–5, hanya setelah pesanan berstatus selesai; rata-rata rating dihitung untuk reputasi penyedia
- Daftar wishlist dan pencarian tersimpan bersifat **privat** (hanya pemilik yang bisa akses)

**Konvensi kode:**
- Ikuti PSR-12 dan konvensi penamaan Laravel (Model singular PascalCase, tabel plural snake_case)
- Controller resource (`index, create, store, show, edit, update, destroy`) untuk modul CRUD
- Jangan pakai raw query dengan input user; gunakan Eloquent / query builder dengan binding
- Status disimpan sebagai enum/konstanta, bukan string bebas:
  - Request jasa: `menunggu`, `diterima`, `ditolak`, `dibatalkan`
  - Pengerjaan: `menunggu`, `dikerjakan`, `revisi`, `selesai`
  - Laporan: `baru`, `diproses`, `selesai`
  - Moderasi: `tampil`, `disembunyikan`, `dihapus`
- File upload: validasi mime & ukuran, simpan di `storage/app/public/...`, jangan simpan path absolut

### 4.4 🖥️ Frontend Developer

**Tanggung jawab:**
- Implementasi Blade view + Tailwind sesuai rancangan UI/UX
- Komponen Blade reusable (`resources/views/components/`) untuk button, input, card, modal, badge status, alert
- Interaksi ringan dengan JavaScript (konfirmasi, preview gambar, filter, dll.)
- Memastikan tampilan responsif dan semua state (loading/empty/error) tersedia

**Aturan:**
- Selalu sertakan `@csrf` di form dan `@method` untuk PUT/PATCH/DELETE
- Output data user wajib di-escape (`{{ }}`), hindari `{!! !!}` kecuali konten sudah disanitasi
- Tampilkan pesan validasi per field (`@error`) dan isi ulang input lama (`old()`)
- Menu dan tombol aksi disembunyikan sesuai permission (`@can`), tapi otorisasi tetap dicek di backend
- Layout dipisah per role: `layouts/admin.blade.php` dan `layouts/app.blade.php`
- Jangan menambah library frontend besar tanpa persetujuan PM

### 4.5 🐙 GitHub Manager

**Tanggung jawab:**
- Mengelola branch, commit message, Pull Request, issue, label, dan milestone
- Menjaga `main` selalu stabil dan bisa dijalankan
- Menyusun deskripsi PR dan changelog

**Strategi branch:**
- `main` → kode stabil / siap demo
- `develop` → integrasi seluruh anggota
- `feature/PBI-xx-nama-singkat` → fitur per PBI (contoh: `feature/PBI-11-pesanan`)
- `fix/<deskripsi>` → perbaikan bug
- `chore/<deskripsi>` → konfigurasi, dependensi, dokumentasi

**Commit message (Conventional Commits):**
```
<type>(PBI-xx): <ringkasan singkat dalam bahasa Indonesia>
```
Type: `feat`, `fix`, `refactor`, `style`, `test`, `docs`, `chore`, `db`
Contoh: `feat(PBI-12): tambah fitur tawaran balik pada negosiasi harga`

**Aturan PR:**
- Satu PR = satu PBI atau satu perbaikan; merge ke `develop`, bukan langsung ke `main`
- Minimal 1 reviewer dari anggota lain sebelum merge
- Wajib lulus `php artisan test` dan tidak ada konflik migration
- Jangan pernah commit `.env`, kredensial, atau file di `vendor/` dan `node_modules/`
- Template deskripsi PR:
  ```
  ## PBI / FR
  PBI-xx — <nama> (FR-xx)
  ## Perubahan
  - ...
  ## Cara Uji
  1. php artisan migrate:fresh --seed
  2. ...
  ## Screenshot (jika ada perubahan UI)
  ## Checklist
  - [ ] Acceptance criteria terpenuhi
  - [ ] Seeder tersedia
  - [ ] Test lulus
  - [ ] Tidak menulis ke tabel PBI lain
  ```

**Label:** `PBI-xx`, `sprint-1`, `sprint-2`, `bug`, `ui`, `backend`, `frontend`, `blocked`, `out-of-scope`
**Milestone:** `Sprint 1`, `Sprint 2`

---

## 5. Product Backlog & Pembagian Tugas

### Jadwal Sprint
- **Sprint 1** (Week 6–10): 19 Oktober – 22 November 2026 — fondasi & fitur inti: autentikasi, profil, jasa, kategori, voucher, request, pesanan, pembayaran, notifikasi
- **Sprint 2** (Week 11–16): 23 November 2026 – 3 Januari 2027 — fitur lanjutan: skill, portfolio, wishlist, pencarian tersimpan, negosiasi, status pengerjaan, chat, review, laporan, Log Admin, penelusuran & detail jasa, integrasi dashboard, pengujian, UAT, bug fix, dokumentasi, deployment, demo akhir

### PBI Tim (dikerjakan seluruh anggota, bukan PBI individu)
- **Fondasi Bersama** (Sprint 1–2) — registrasi, login, logout, verifikasi mahasiswa, akun, role, hak akses → tabel `users`, `roles`, `permissions`, `role_permissions` (FR-01, FR-02, FR-05, FR-06)
- **Integrasi Bersama** (Sprint 2) — dashboard transaksi & penghasilan, pengujian, UAT, perbaikan bug → tidak punya tabel sendiri, hanya membaca data modul lain (FR-03, FR-04)

### PBI Individu
**Earliyanza**
- PBI-01 Manajemen Profil Pengguna — `profiles`, `experiences` — Sprint 1 (FR-07)
- PBI-02 Manajemen Skill — `skills`, `user_skills` — Sprint 2 (FR-08)
- PBI-03 Manajemen Log Admin — `admin_logs`, `moderasi_konten` — Sprint 2 (FR-09)

**Anindya**
- PBI-04 Manajemen Jasa — `services` — Sprint 1 (FR-10)
- PBI-05 Manajemen Kategori Jasa — `service_categories` — Sprint 1 (FR-11)
- PBI-06 Manajemen Portfolio — `portfolios` — Sprint 2 (FR-12)

**Rizal**
- PBI-07 Manajemen Wishlist/Favorit — `favorites` — Sprint 2 (FR-13)
- PBI-08 Manajemen Pencarian Tersimpan — `saved_searches` — Sprint 2 (FR-14)
- PBI-09 Manajemen Voucher/Promo — `vouchers` — Sprint 1 (FR-15)
- Tambahan: Penelusuran Jasa (FR-25) & Detail Jasa (FR-26) — Sprint 2

**Mahendra**
- PBI-10 Manajemen Request Jasa — `service_requests` — Sprint 1 (FR-16)
- PBI-11 Manajemen Pesanan — `orders` — Sprint 1 (FR-17)
- PBI-12 Manajemen Negosiasi Harga — `negotiations` — Sprint 2 (FR-18)

**Firzatullah**
- PBI-13 Manajemen Pembayaran — `payments` — Sprint 1 (FR-19)
- PBI-14 Manajemen Status Pengerjaan — `work_progress` — Sprint 2 (FR-20)
- PBI-15 Manajemen Chat/Komunikasi — `messages` — Sprint 2 (FR-21)

**Aprilia**
- PBI-16 Manajemen Review dan Rating — `reviews` — Sprint 2 (FR-22)
- PBI-17 Manajemen Notifikasi — `notifications` — Sprint 1 (FR-23)
- PBI-18 Manajemen Laporan/Pengaduan — `reports` — Sprint 2 (FR-24)

### Peran Scrum
- Scrum Master: `[ditentukan tim]`
- Product Owner: `[ditentukan tim]`
- Saya (pemilik repo ini) mengerjakan: `[isi PBI yang sedang dikerjakan]`

---

## 6. Alur Kerja Standar Claude

Untuk setiap permintaan fitur:

1. **[PM]** Identifikasi PBI/FR terkait, PIC, Sprint, lalu tulis acceptance criteria. Tanyakan jika ambigu.
2. **[UI/UX]** Jelaskan user flow + struktur halaman + state yang dibutuhkan (jika ada tampilan).
3. **[Backend]** Migration → Model + relasi → Seeder/Factory → Form Request → Policy → Controller → Route → Feature test.
4. **[Frontend]** Blade view + komponen + validasi tampilan + responsif.
5. **[GitHub Manager]** Usulkan nama branch, daftar commit, dan deskripsi PR.
6. **[PM]** Ringkas hasil: apa yang selesai, cara menguji, dan apa langkah berikutnya.

Untuk bug fix: mulai dari **[Backend]/[Frontend]** reproduksi masalah → perbaikan → test → **[GitHub Manager]** branch `fix/...` dan commit.

## 7. Definition of Done

Sebuah PBI dianggap selesai jika:
- [ ] Semua acceptance criteria terpenuhi
- [ ] Operasi CRUD lengkap berjalan dan dibatasi sesuai role
- [ ] Seeder tersedia dan `php artisan migrate:fresh --seed` berjalan tanpa error
- [ ] Feature test lulus (`php artisan test`)
- [ ] Tampilan responsif dan punya empty/error state
- [ ] Tidak ada celah SQL injection, XSS, CSRF
- [ ] Notifikasi terkait (jika ada) terkirim otomatis
- [ ] PR sudah direview dan di-merge ke `develop`

## 8. Perintah Penting

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm run dev            # Vite + Tailwind
php artisan serve
php artisan test
```

## 9. Larangan

- Jangan menambah fitur di luar proposal tanpa persetujuan user
- Jangan menghapus atau mengubah migration yang sudah di-merge ke `develop`; buat migration baru
- Jangan menulis ke tabel milik PBI lain (kecuali `notifications` lewat event)
- Jangan commit secret / `.env`
- Jangan mengganti tech stack (Laravel, Blade, MySQL) tanpa persetujuan tim
