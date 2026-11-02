<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * VoucherController – PBI-09
 * PIC: Rizal
 */
class VoucherController extends Controller
{
    public function index()   { /* TODO: PBI-09 – daftar */ return view('admin.'.strtolower(str_replace('Controller','',str_replace('Admin','',' VoucherController'))).'.index'); }
    public function create()  { /* TODO: PBI-09 – form tambah */ }
    public function store(Request $request)  { /* TODO: PBI-09 – simpan */ }
    public function show($id) { /* TODO: PBI-09 – detail */ }
    public function edit($id) { /* TODO: PBI-09 – form edit */ }
    public function update(Request $request, $id) { /* TODO: PBI-09 – update */ }
    public function destroy($id) { /* TODO: PBI-09 – hapus */ }
}
