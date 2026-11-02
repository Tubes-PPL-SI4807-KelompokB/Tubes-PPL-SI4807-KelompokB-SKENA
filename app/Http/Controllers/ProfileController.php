<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ProfileController – PBI-01
 * PIC: Earliyanza
 */
class ProfileController extends Controller
{
    /** PBI-01 – daftar */
    public function index() {}

    /** PBI-01 – form tambah */
    public function create() {}

    /** PBI-01 – simpan */
    public function store(Request $request) {}

    /** PBI-01 – detail */
    public function show($id) {}

    /** PBI-01 – form edit */
    public function edit($id) {}

    /** PBI-01 – update */
    public function update(Request $request, $id) {}

    /** PBI-01 – hapus */
    public function destroy($id) {}
}
