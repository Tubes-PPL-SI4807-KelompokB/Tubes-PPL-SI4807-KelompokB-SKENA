<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

/**
 * PBI-05: CRUD Manajemen Kategori Jasa (Sprint 1)
 * PIC: Anindya Putri Prameswari (102022400026)
 * Aktor: Admin (FR-11)
 */
class ServiceCategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori jasa beserta jumlah jasa terkait.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = ServiceCategory::withCount('services')
            ->when($search, function ($query, $search) {
                return $query->search($search);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    /**
     * Menampilkan form tambah kategori jasa baru.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori jasa baru ke database.
     */
    public function store(CategoryRequest $request)
    {
        ServiceCategory::create($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori jasa berhasil ditambahkan.');
    }

    /**
     * Detail kategori
     */
    public function show(ServiceCategory $category)
    {
        $category->loadCount('services');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Menampilkan form edit kategori jasa.
     */
    public function edit(ServiceCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori jasa.
     */
    public function update(CategoryRequest $request, ServiceCategory $category)
    {
        $category->update($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori jasa berhasil diperbarui.');
    }

    /**
     * Menghapus kategori jasa.
     * Mencegah penghapusan jika kategori masih memiliki jasa terkait.
     */
    public function destroy(ServiceCategory $category)
    {
        if ($category->services()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa jasa aktif.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori jasa berhasil dihapus.');
    }
}
