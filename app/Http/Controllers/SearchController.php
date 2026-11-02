<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * SearchController – FR-25
 * PIC: Rizal
 */
class SearchController extends Controller
{
    /** FR-25 – daftar */
    public function index() {}

    /** FR-25 – form tambah */
    public function create() {}

    /** FR-25 – simpan */
    public function store(Request $request) {}

    /** FR-25 – detail */
    public function show($id) {}

    /** FR-25 – form edit */
    public function edit($id) {}

    /** FR-25 – update */
    public function update(Request $request, $id) {}

    /** FR-25 – hapus */
    public function destroy($id) {}
}
