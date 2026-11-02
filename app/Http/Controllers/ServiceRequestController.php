<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ServiceRequestController – PBI-10
 * PIC: Mahendra
 */
class ServiceRequestController extends Controller
{
    /** PBI-10 – daftar */
    public function index() {}

    /** PBI-10 – form tambah */
    public function create() {}

    /** PBI-10 – simpan */
    public function store(Request $request) {}

    /** PBI-10 – detail */
    public function show($id) {}

    /** PBI-10 – form edit */
    public function edit($id) {}

    /** PBI-10 – update */
    public function update(Request $request, $id) {}

    /** PBI-10 – hapus */
    public function destroy($id) {}
}
