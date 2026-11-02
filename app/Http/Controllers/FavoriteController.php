<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * FavoriteController – PBI-07
 * PIC: Rizal
 */
class FavoriteController extends Controller
{
    /** PBI-07 – daftar */
    public function index() {}

    /** PBI-07 – form tambah */
    public function create() {}

    /** PBI-07 – simpan */
    public function store(Request $request) {}

    /** PBI-07 – detail */
    public function show($id) {}

    /** PBI-07 – form edit */
    public function edit($id) {}

    /** PBI-07 – update */
    public function update(Request $request, $id) {}

    /** PBI-07 – hapus */
    public function destroy($id) {}
}
