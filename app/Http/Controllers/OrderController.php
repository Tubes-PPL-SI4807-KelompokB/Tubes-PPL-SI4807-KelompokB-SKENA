<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * OrderController – PBI-11
 * PIC: Mahendra
 */
class OrderController extends Controller
{
    /** PBI-11 – daftar */
    public function index() {}

    /** PBI-11 – form tambah */
    public function create() {}

    /** PBI-11 – simpan */
    public function store(Request $request) {}

    /** PBI-11 – detail */
    public function show($id) {}

    /** PBI-11 – form edit */
    public function edit($id) {}

    /** PBI-11 – update */
    public function update(Request $request, $id) {}

    /** PBI-11 – hapus */
    public function destroy($id) {}
}
