<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ServiceDetailController – FR-26
 * PIC: Rizal
 */
class ServiceDetailController extends Controller
{
    /** FR-26 – daftar */
    public function index() {}

    /** FR-26 – form tambah */
    public function create() {}

    /** FR-26 – simpan */
    public function store(Request $request) {}

    /** FR-26 – detail */
    public function show($id) {}

    /** FR-26 – form edit */
    public function edit($id) {}

    /** FR-26 – update */
    public function update(Request $request, $id) {}

    /** FR-26 – hapus */
    public function destroy($id) {}
}
