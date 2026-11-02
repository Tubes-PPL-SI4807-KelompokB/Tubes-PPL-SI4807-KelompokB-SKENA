<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * SavedSearchController – PBI-08
 * PIC: Rizal
 */
class SavedSearchController extends Controller
{
    /** PBI-08 – daftar */
    public function index() {}

    /** PBI-08 – form tambah */
    public function create() {}

    /** PBI-08 – simpan */
    public function store(Request $request) {}

    /** PBI-08 – detail */
    public function show($id) {}

    /** PBI-08 – form edit */
    public function edit($id) {}

    /** PBI-08 – update */
    public function update(Request $request, $id) {}

    /** PBI-08 – hapus */
    public function destroy($id) {}
}
