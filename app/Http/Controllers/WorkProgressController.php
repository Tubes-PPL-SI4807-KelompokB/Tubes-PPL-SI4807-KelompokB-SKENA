<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * WorkProgressController – PBI-14
 * PIC: Firzatullah
 */
class WorkProgressController extends Controller
{
    /** PBI-14 – daftar */
    public function index() {}

    /** PBI-14 – form tambah */
    public function create() {}

    /** PBI-14 – simpan */
    public function store(Request $request) {}

    /** PBI-14 – detail */
    public function show($id) {}

    /** PBI-14 – form edit */
    public function edit($id) {}

    /** PBI-14 – update */
    public function update(Request $request, $id) {}

    /** PBI-14 – hapus */
    public function destroy($id) {}
}
