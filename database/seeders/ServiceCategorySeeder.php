<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * PBI-05: Seeder Kategori Jasa (Sprint 1)
 * PIC: Anindya Putri Prameswari (102022400026)
 */
class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Desain',
                'deskripsi' => 'Layanan desain presentasi PowerPoint, poster kegiatan kampus, logo organisasi, dan UI/UX mockup.',
            ],
            [
                'nama_kategori' => 'Tutor & Akademik',
                'deskripsi' => 'Bimbingan belajar mata kuliah pemrograman, SQL database, matematika teknik, statistika, dan akuntansi.',
            ],
            [
                'nama_kategori' => 'Video & Animasi',
                'deskripsi' => 'Pengeditan video pendek TikTok/Reels, video perkenalan UKM, subtitle, dan animasi sederhana.',
            ],
            [
                'nama_kategori' => 'Web & Teknologi',
                'deskripsi' => 'Pembuatan landing page sederhana, setup portofolio web, styling CSS/Tailwind, dan instalasi sistem.',
            ],
            [
                'nama_kategori' => 'Olah Data & Office',
                'deskripsi' => 'Bantuan rumus/formula Microsoft Excel, pengolahan spreadsheet Google Sheets, format laporan, dan transkripsi.',
            ],
            [
                'nama_kategori' => 'Fotografi & Media',
                'deskripsi' => 'Foto produk katalog UMKM mahasiswa, foto profil profesional LinkedIn, dan dokumentasi kegiatan kampus.',
            ],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::firstOrCreate(
                ['nama_kategori' => $cat['nama_kategori']],
                ['deskripsi' => $cat['deskripsi']]
            );
        }
    }
}
