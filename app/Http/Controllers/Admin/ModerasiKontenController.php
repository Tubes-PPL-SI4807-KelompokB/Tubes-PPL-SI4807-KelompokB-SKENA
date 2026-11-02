<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ModerasiKontenController – PBI-03
 * PIC: Earliyanza
 */
class ModerasiKontenController extends Controller
{
    public function index()   { /* TODO: PBI-03 – daftar */ return view('admin.'.strtolower(str_replace('Controller','',str_replace('Admin','',' ModerasiKontenController'))).'.index'); }
    public function create()  { /* TODO: PBI-03 – form tambah */ }
    public function store(Request $request)  { /* TODO: PBI-03 – simpan */ }
    public function show($id) { /* TODO: PBI-03 – detail */ }
    public function edit($id) { /* TODO: PBI-03 – form edit */ }
    public function update(Request $request, $id) { /* TODO: PBI-03 – update */ }
    public function destroy($id) { /* TODO: PBI-03 – hapus */ }
}
