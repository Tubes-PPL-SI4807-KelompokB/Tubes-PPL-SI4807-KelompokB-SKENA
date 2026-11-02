<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * DashboardController – FR-04
 * PIC: Tim
 */
class DashboardController extends Controller
{
    /** FR-04 – daftar */
    public function index() {}

    /** FR-04 – form tambah */
    public function create() {}

    /** FR-04 – simpan */
    public function store(Request $request) {}

    /** FR-04 – detail */
    public function show($id) {}

    /** FR-04 – form edit */
    public function edit($id) {}

    /** FR-04 – update */
    public function update(Request $request, $id) {}

    /** FR-04 – hapus */
    public function destroy($id) {}
}
