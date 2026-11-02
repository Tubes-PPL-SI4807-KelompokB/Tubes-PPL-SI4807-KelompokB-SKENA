<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * PortfolioController – PBI-06
 * PIC: Anindya
 */
class PortfolioController extends Controller
{
    /** PBI-06 – daftar */
    public function index() {}

    /** PBI-06 – form tambah */
    public function create() {}

    /** PBI-06 – simpan */
    public function store(Request $request) {}

    /** PBI-06 – detail */
    public function show($id) {}

    /** PBI-06 – form edit */
    public function edit($id) {}

    /** PBI-06 – update */
    public function update(Request $request, $id) {}

    /** PBI-06 – hapus */
    public function destroy($id) {}
}
