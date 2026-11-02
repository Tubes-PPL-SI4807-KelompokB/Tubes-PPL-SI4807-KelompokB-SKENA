<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ServiceController – PBI-04
 * PIC: Anindya
 */
class ServiceController extends Controller
{
    /** PBI-04 – daftar */
    public function index() {}

    /** PBI-04 – form tambah */
    public function create() {}

    /** PBI-04 – simpan */
    public function store(Request $request) {}

    /** PBI-04 – detail */
    public function show($id) {}

    /** PBI-04 – form edit */
    public function edit($id) {}

    /** PBI-04 – update */
    public function update(Request $request, $id) {}

    /** PBI-04 – hapus */
    public function destroy($id) {}
}
