<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * PBI-04: Seeder Data Jasa Mahasiswa (Sprint 1)
 * PIC: Anindya Putri Prameswari (102022400026)
 * Data disesuaikan persis dengan contoh di Proposal SKENA.
 */
class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $provider = User::where('email', 'anindya@student.telkomuniversity.ac.id')->first()
            ?? User::first();

        if (!$provider) {
            return;
        }

        $catDesain = ServiceCategory::where('nama_kategori', 'Desain')->first();
        $catTutor = ServiceCategory::where('nama_kategori', 'Tutor & Akademik')->first();
        $catVideo = ServiceCategory::where('nama_kategori', 'Video & Animasi')->first();
        $catWeb = ServiceCategory::where('nama_kategori', 'Web & Teknologi')->first();
        $catData = ServiceCategory::where('nama_kategori', 'Olah Data & Office')->first();
        $catFoto = ServiceCategory::where('nama_kategori', 'Fotografi & Media')->first();

        $defaultCatId = ServiceCategory::first()?->id ?? 1;

        $services = [
            [
                'provider_id' => $provider->id,
                'category_id' => $catDesain ? $catDesain->id : $defaultCatId,
                'judul' => 'Desain presentasi (PPT)',
                'deskripsi' => 'Saya membantu merapikan slide presentasi tugas kuliah: tata letak rapi, ikon modern, dan palet warna yang konsisten. Maksimal 15 slide per pesanan, revisi 2 kali.',
                'harga' => 20000,
                'durasi' => '2 hari',
                'status' => 'aktif',
            ],
            [
                'provider_id' => $provider->id,
                'category_id' => $catFoto ? $catFoto->id : $defaultCatId,
                'judul' => 'Foto produk sederhana',
                'deskripsi' => 'Foto produk katalog untuk jualan makanan atau merchandise UKM kampus. Menggunakan mini studio box dan pencahayaan memadai. Hasil 5 foto teredit.',
                'harga' => 50000,
                'durasi' => '3 hari',
                'status' => 'aktif',
            ],
            [
                'provider_id' => $provider->id,
                'category_id' => $catData ? $catData->id : $defaultCatId,
                'judul' => 'Bantuan Excel dasar',
                'deskripsi' => 'Membantu merapikan data tabel, rumus VLOOKUP/XLOOKUP, IF bertingkat, Pivot Table, dan pembuatan grafik visual untuk tugas kuliah atau laporan keuangan organisasi.',
                'harga' => 25000,
                'durasi' => '1 hari',
                'status' => 'aktif',
            ],
            [
                'provider_id' => $provider->id,
                'category_id' => $catVideo ? $catVideo->id : $defaultCatId,
                'judul' => 'Edit video 1 menit',
                'deskripsi' => 'Editing video pendek untuk reels Instagram, TikTok kegiatan kampus, pemotongan transisi dinamis, penambahan teks/caption otomatis, dan backsound bebas hak cipta.',
                'harga' => 30000,
                'durasi' => '2 hari',
                'status' => 'aktif',
            ],
            [
                'provider_id' => $provider->id,
                'category_id' => $catWeb ? $catWeb->id : $defaultCatId,
                'judul' => 'Setup website portofolio',
                'deskripsi' => 'Membantu mahasiswa membuat website profil/portofolio online dengan HTML/CSS/Tailwind, deploy gratis ke GitHub Pages atau Vercel agar siap dipamerkan ke calon klien/magang.',
                'harga' => 50000,
                'durasi' => '3 hari',
                'status' => 'aktif',
            ],
            [
                'provider_id' => $provider->id,
                'category_id' => $catTutor ? $catTutor->id : $defaultCatId,
                'judul' => 'Tutor SQL per jam',
                'deskripsi' => 'Sesi bimbingan 1-on-1 belajar Query SQL: DDL, DML, JOIN tabel, grouping, subquery, dan latihan soal praktikum basis data hingga paham tuntas.',
                'harga' => 30000,
                'durasi' => '1 jam/sesi',
                'status' => 'aktif',
            ],
        ];

        foreach ($services as $svc) {
            Service::firstOrCreate(
                [
                    'provider_id' => $svc['provider_id'],
                    'judul' => $svc['judul'],
                ],
                $svc
            );
        }
    }
}
