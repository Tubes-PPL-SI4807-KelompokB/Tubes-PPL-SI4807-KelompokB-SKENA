<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
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

    /** Gambar sampul per kelompok kategori (sampai jasa punya foto sendiri) */
    private const IMAGES = [
        'desain'  => 'jasa-ppt.jpg',
        'data'    => 'jasa-olah-data.jpg',
        'video'   => 'jasa-video.jpg',
        'tutor'   => 'jasa-tutor-koding.jpg',
        'dokumen' => 'jasa-dokumen.jpg',
        'web'     => 'jasa-web.jpg',
    ];

    /** Kata kunci nama kategori (tabel service_categories) => slug filter */
    private const KEYWORDS = [
        'desain' => 'desain', 'foto' => 'desain',
        'data'   => 'data',   'office' => 'data', 'excel' => 'data',
        'video'  => 'video',  'animasi' => 'video',
        'tutor'  => 'tutor',  'akademik' => 'tutor',
        'dokumen' => 'dokumen', 'tulis' => 'dokumen',
        'web'    => 'web',    'teknologi' => 'web',
    ];

    /** FR-25 – daftar jasa aktif dengan pencarian, filter kategori, dan urutan */
    public function index(Request $request)
    {
        $q    = trim((string) $request->query('q', ''));
        $cat  = array_key_exists($request->query('kategori'), self::CATEGORIES) ? $request->query('kategori') : null;
        $sort = array_key_exists($request->query('urut'), self::SORTS) ? $request->query('urut') : 'terbaru';

        // slug kelompok => id kategori di database
        $groups = ServiceCategory::pluck('nama_kategori', 'id')
            ->map(fn ($name) => $this->groupOf($name));

        $paginator = Service::active()
            ->with(['provider:id,name', 'category:id,nama_kategori'])
            ->when($cat, fn ($query) => $query->whereIn('category_id', $groups->filter(fn ($g) => $g === $cat)->keys()))
            ->when($q !== '', fn ($query) => $query->where(fn ($sub) => $sub
                ->where('judul', 'like', "%{$q}%")
                ->orWhere('deskripsi', 'like', "%{$q}%")
                ->orWhereHas('provider', fn ($p) => $p->where('name', 'like', "%{$q}%"))))
            ->when($sort === 'termurah', fn ($query) => $query->orderBy('harga'))
            ->when($sort === 'termahal', fn ($query) => $query->orderByDesc('harga'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $paginator->setCollection($paginator->getCollection()->map(fn (Service $s) => $this->toCard($s, $groups)));

        // Jumlah jasa aktif per kelompok kategori untuk tab filter
        $counts = Service::active()
            ->selectRaw('category_id, count(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id')
            ->groupBy(fn ($total, $categoryId) => $groups[$categoryId] ?? null, true)
            ->map(fn ($items) => $items->sum());

        return view('home.index', [
            'services'   => $paginator,
            'total'      => $counts->sum(),
            'counts'     => $counts,
            'categories' => self::CATEGORIES,
            'sorts'      => self::SORTS,
            'q'          => $q,
            'cat'        => $cat,
            'sort'       => $sort,
        ]);
    }

    /** Tentukan kelompok filter dari nama kategori di database */
    private function groupOf(?string $categoryName): ?string
    {
        $name = Str::lower((string) $categoryName);

        foreach (self::KEYWORDS as $keyword => $slug) {
            if (Str::contains($name, $keyword)) {
                return $slug;
            }
        }

        return null;
    }

    /** Bentuk data kartu katalog yang dipakai view home.index */
    private function toCard(Service $s, $groups): array
    {
        $group = $groups[$s->category_id] ?? null;

        return [
            'id'         => $s->id,
            'img'        => self::IMAGES[$group] ?? 'jasa-ppt.jpg',
            'badge'      => $s->created_at?->gt(now()->subDays(14)) ? 'BARU' : 'JASA',
            'hot'        => false,
            'group'      => $group,
            'cat'        => $s->category?->nama_kategori ?? 'Lainnya',
            'rating'     => null, // rating diisi setelah PBI-16 Review tersedia
            'reviews'    => 0,
            'title'      => $s->judul,
            'provider'   => $s->provider?->name ?? 'Penyedia SKENA',
            'providerId' => $s->provider_id,
            'priceLabel' => 'Mulai dari',
            'amount'     => (float) $s->harga,
            'unit'       => null,
            'price'      => $s->formatted_harga,
            'orderUrl'   => route('requests.create', ['service_id' => $s->id]),
        ];
    }
}
