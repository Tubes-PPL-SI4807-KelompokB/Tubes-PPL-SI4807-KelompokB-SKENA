<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

/**
 * ServiceCategoryController – PBI-05 Manajemen Kategori Jasa (FR-11)
 * PIC: Anindya Putri Prameswari
 *
 * Desain: p05-1 daftar + cari, p05-2 slide-over tambah/ubah,
 * p05-3 hapus (ditolak bila masih dipakai jasa), keadaan kosong, toast.
 */
class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q'));

        $categories = ServiceCategory::withCount('services')
            ->search($search)
            ->orderBy('nama_kategori')
            ->paginate(15)
            ->withQueryString();

        $total = ServiceCategory::count();

        return view('admin.categories.index', compact('categories', 'search', 'total'));
    }

    /** Form tambah/ubah tampil sebagai slide-over di halaman daftar. */
    public function create()
    {
        return redirect()->route('admin.categories.index', ['tambah' => 1]);
    }

    public function show(ServiceCategory $category)
    {
        return redirect()->route('admin.categories.index', ['ubah' => $category->id]);
    }

    public function edit(ServiceCategory $category)
    {
        return redirect()->route('admin.categories.index', ['ubah' => $category->id]);
    }

    public function store(CategoryRequest $request)
    {
        $category = ServiceCategory::create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->nama_kategori}\" berhasil disimpan");
    }

    public function update(CategoryRequest $request, ServiceCategory $category)
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index', $request->only('q', 'page'))
            ->with('success', "Kategori \"{$category->nama_kategori}\" berhasil disimpan");
    }

    public function destroy(ServiceCategory $category)
    {
        $used = $category->services()->count();

        if ($used > 0) {
            return back()->with('error', "Kategori \"{$category->nama_kategori}\" masih dipakai {$used} jasa. Pindahkan jasa tersebut ke kategori lain sebelum menghapus.");
        }

        $name = $category->nama_kategori;
        $category->delete();

        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus");
    }
}
