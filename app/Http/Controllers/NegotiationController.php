<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * NegotiationController – PBI-12
 * PIC: Mahendra
 */
class NegotiationController extends Controller
{
    /** PBI-12 – daftar */
    public function index() {}

    /** PBI-12 – form tambah */
    public function create() {}

    /** PBI-12 – simpan */
    public function store(Request $request) {}

    /** PBI-12 – detail */
    public function show($id) {}

    /** PBI-12 – form edit */
    public function edit($id) {}

    /** PBI-12 – update */
    public function update(Request $request, $id) {}

    /** PBI-12 – hapus */
    public function destroy($id) {}
}
