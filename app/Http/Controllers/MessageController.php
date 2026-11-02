<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * MessageController – PBI-15
 * PIC: Firzatullah
 */
class MessageController extends Controller
{
    /** PBI-15 – daftar */
    public function index() {}

    /** PBI-15 – form tambah */
    public function create() {}

    /** PBI-15 – simpan */
    public function store(Request $request) {}

    /** PBI-15 – detail */
    public function show($id) {}

    /** PBI-15 – form edit */
    public function edit($id) {}

    /** PBI-15 – update */
    public function update(Request $request, $id) {}

    /** PBI-15 – hapus */
    public function destroy($id) {}
}
