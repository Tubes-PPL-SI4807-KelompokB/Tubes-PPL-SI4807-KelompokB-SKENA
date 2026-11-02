<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ReviewController – PBI-16
 * PIC: Aprilia
 */
class ReviewController extends Controller
{
    /** PBI-16 – daftar */
    public function index() {}

    /** PBI-16 – form tambah */
    public function create() {}

    /** PBI-16 – simpan */
    public function store(Request $request) {}

    /** PBI-16 – detail */
    public function show($id) {}

    /** PBI-16 – form edit */
    public function edit($id) {}

    /** PBI-16 – update */
    public function update(Request $request, $id) {}

    /** PBI-16 – hapus */
    public function destroy($id) {}
}
