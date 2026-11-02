<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * RoleController – FR-06
 * PIC: Tim
 */
class RoleController extends Controller
{
    public function index()   { /* TODO: FR-06 – daftar */ return view('admin.'.strtolower(str_replace('Controller','',str_replace('Admin','',' RoleController'))).'.index'); }
    public function create()  { /* TODO: FR-06 – form tambah */ }
    public function store(Request $request)  { /* TODO: FR-06 – simpan */ }
    public function show($id) { /* TODO: FR-06 – detail */ }
    public function edit($id) { /* TODO: FR-06 – form edit */ }
    public function update(Request $request, $id) { /* TODO: FR-06 – update */ }
    public function destroy($id) { /* TODO: FR-06 – hapus */ }
}
