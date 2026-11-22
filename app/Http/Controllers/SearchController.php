<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * SearchController – FR-25 (Jelajahi Jasa)
 * PIC: Rizal
 */
class SearchController extends Controller
{
    /** Kategori: slug => label */
    public const CATEGORIES = [
        'desain'  => 'Desain',
        'data'    => 'Olah Data',
        'video'   => 'Video',
        'tutor'   => 'Tutor',
        'dokumen' => 'Dokumen',
        'web'     => 'Web',
    ];

    public const SORTS = [
        'terbaru'  => 'Terbaru',
        'rating'   => 'Rating tertinggi',
        'termurah' => 'Harga termurah',
        'termahal' => 'Harga termahal',
    ];

    /** FR-25 – daftar jasa dengan pencarian, filter kategori, dan urutan */
    public function index(Request $request)
    {
        $q    = trim((string) $request->query('q', ''));
        $cat  = array_key_exists($request->query('kategori'), self::CATEGORIES) ? $request->query('kategori') : null;
        $sort = array_key_exists($request->query('urut'), self::SORTS) ? $request->query('urut') : 'terbaru';

        $all = collect($this->sampleServices());

        $services = $all
            ->when($cat, fn ($c) => $c->where('group', $cat))
            ->when($q !== '', fn ($c) => $c->filter(fn ($s) => Str::contains(
                Str::lower($s['title'].' '.$s['cat'].' '.$s['provider']), Str::lower($q)
            )))
            ->pipe(fn ($c) => match ($sort) {
                'rating'   => $c->sortByDesc(fn ($s) => [$s['rating'], $s['reviews']]),
                'termurah' => $c->sortBy('amount'),
                'termahal' => $c->sortByDesc('amount'),
                default    => $c,
            })
            ->values();

        $counts = $all->countBy('group');

        return view('home.index', [
            'services'   => $services,
            'total'      => $all->count(),
            'counts'     => $counts,
            'categories' => self::CATEGORIES,
            'sorts'      => self::SORTS,
            'q'          => $q,
            'cat'        => $cat,
            'sort'       => $sort,
        ]);
    }

    /**
     * Data contoh sampai tabel services (PBI-04) terisi.
     * Urutan array = urutan "Terbaru".
     */
    private function sampleServices(): array
    {
        $rows = [
            ['jasa-ppt.jpg',          'BARU',       false, 'desain',  'Desain PPT',    '5.0', 19, 'Desain PPT Sidang & Seminar Skripsi Cepat',      'Nadia S. (DKV ITB)',              'Mulai dari', 20000, null],
            ['jasa-olah-data.jpg',    'POPULER',    true,  'data',    'Olah Data',     '4.9', 42, 'Olah Data Skripsi SPSS & Interpretasi Excel',    'Rian Pratama (Statistika UNPAD)', 'Mulai dari', 50000, null],
            ['jasa-video.jpg',        'BARU',       false, 'video',   'Video Editing', '4.8', 11, 'Editing Video Reels / TikTok Tugas Kampus',      'Dimas K. (Ilkom UI)',             'Mulai dari', 35000, null],
            ['jasa-tutor-koding.jpg', 'TOP SELLER', true,  'tutor',   'Tutor Koding',  '5.0', 34, 'Tutor Privat Koding Python & SQL Tugas Kuliah', 'Fathir A. (Informatika)',         'Biaya sesi', 40000, '/jam'],
            ['jasa-dokumen.jpg',      'BARU',       false, 'dokumen', 'Dokumen',       '4.7', 8,  'Rapikan Format & Daftar Pustaka Skripsi',        'Salsa M. (Sastra UGM)',           'Mulai dari', 25000, null],
            ['jasa-web.jpg',          'BARU',       false, 'web',     'Web',           '4.9', 15, 'Bikin Landing Page Organisasi Kampus',           'Arga P. (Sistem Informasi)',      'Mulai dari', 75000, null],
            ['jasa-video.jpg',        'POPULER',    true,  'video',   'Video Editing', '4.8', 27, 'Edit Video Dokumentasi KKN dan Organisasi',      'Dimas K. (Ilkom UI)',             'Mulai dari', 45000, null],
            ['jasa-ppt.jpg',          'BARU',       false, 'desain',  'Desain Grafis', '4.8', 9,  'Desain Poster dan Feed Instagram Acara Kampus',  'Bella T. (DKV Telkom)',           'Mulai dari', 30000, null],
            ['jasa-olah-data.jpg',    'POPULER',    true,  'data',    'Olah Data',     '4.8', 21, 'Dashboard Excel untuk Laporan Keuangan UKM',     'Nabila P. (Akuntansi)',           'Mulai dari', 35000, null],
            ['jasa-tutor-koding.jpg', 'BARU',       false, 'tutor',   'Tutor Koding',  '4.9', 16, 'Tutor SQL dan Basis Data untuk Pemula',          'Dimas K. (Informatika)',          'Biaya sesi', 30000, '/jam'],
            ['jasa-dokumen.jpg',      'BARU',       false, 'dokumen', 'Dokumen',       '4.7', 6,  'Proofreading Abstrak dan Jurnal Bahasa Inggris', 'Kevin W. (Sastra Inggris)',       'Mulai dari', 30000, null],
            ['jasa-web.jpg',          'POPULER',    true,  'web',     'Web',           '4.7', 12, 'Setup Website Portofolio Pribadi',               'Bagas P. (Sistem Informasi)',     'Mulai dari', 50000, null],
            ['jasa-tutor-koding.jpg', 'BARU',       false, 'tutor',   'Tutor Statistik', '4.8', 10, 'Tutor Statistika Dasar Menjelang UAS',         'Laras D. (Statistika)',           'Biaya sesi', 35000, '/jam'],
            ['jasa-ppt.jpg',          'POPULER',    true,  'desain',  'Desain Grafis', '4.9', 31, 'Desain Logo dan Identitas Visual UKM',           'Reza F. (DKV)',                   'Mulai dari', 60000, null],
            ['jasa-dokumen.jpg',      'BARU',       false, 'dokumen', 'Dokumen',       '4.6', 5,  'Terjemahan Dokumen Indonesia–Inggris',           'Maya R. (Sastra Inggris)',        'Mulai dari', 40000, null],
            ['jasa-video.jpg',        'BARU',       false, 'video',   'Motion Graphic', '4.7', 7, 'Motion Graphic Intro untuk Presentasi',          'Yoga S. (DKV)',                   'Mulai dari', 55000, null],
        ];

        return array_map(fn ($r) => [
            'img' => $r[0], 'badge' => $r[1], 'hot' => $r[2], 'group' => $r[3], 'cat' => $r[4],
            'rating' => $r[5], 'reviews' => $r[6], 'title' => $r[7], 'provider' => $r[8],
            'priceLabel' => $r[9], 'amount' => $r[10], 'unit' => $r[11],
            'price' => 'Rp'.number_format($r[10], 0, ',', '.'),
        ], $rows);
    }
}
