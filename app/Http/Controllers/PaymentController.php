<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * PaymentController – PBI-13
 * PIC: Firzatullah
 */
class PaymentController extends Controller
{
    /** PBI-13 – daftar */
    public function index() {}

    /** PBI-13 – form tambah */
    public function create() {}

    /** PBI-13 – simpan */
    public function store(Request $request) {}

    /** PBI-13 – detail */
    public function show($id) {}

    /** PBI-13 – form edit */
    public function edit($id) {}

    /** PBI-13 – update */
    public function update(Request $request, $id) {}

    /** PBI-13 – hapus */
    public function destroy($id) {}
}
