<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ReportController – PBI-18
 * PIC: Aprilia
 */
class ReportController extends Controller
{
    /** PBI-18 – daftar */
    public function index() {}

    /** PBI-18 – form tambah */
    public function create() {}

    /** PBI-18 – simpan */
    public function store(Request $request) {}

    /** PBI-18 – detail */
    public function show($id) {}

    /** PBI-18 – form edit */
    public function edit($id) {}

    /** PBI-18 – update */
    public function update(Request $request, $id) {}

    /** PBI-18 – hapus */
    public function destroy($id) {}
}
