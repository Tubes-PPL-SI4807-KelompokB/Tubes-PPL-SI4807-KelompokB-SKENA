<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * NotificationController – PBI-17
 * PIC: Aprilia
 */
class NotificationController extends Controller
{
    /** PBI-17 – daftar */
    public function index() {}

    /** PBI-17 – form tambah */
    public function create() {}

    /** PBI-17 – simpan */
    public function store(Request $request) {}

    /** PBI-17 – detail */
    public function show($id) {}

    /** PBI-17 – form edit */
    public function edit($id) {}

    /** PBI-17 – update */
    public function update(Request $request, $id) {}

    /** PBI-17 – hapus */
    public function destroy($id) {}
}
