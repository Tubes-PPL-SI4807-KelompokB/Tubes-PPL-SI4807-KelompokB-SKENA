<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * UserController – FR-05
 * PIC: Tim
 */
class UserController extends Controller
{
    public function index()   { /* TODO: FR-05 – daftar */ return view('admin.'.strtolower(str_replace('Controller','',str_replace('Admin','',' UserController'))).'.index'); }
    public function create()  { /* TODO: FR-05 – form tambah */ }
    public function store(Request $request)  { /* TODO: FR-05 – simpan */ }
    public function show($id) { /* TODO: FR-05 – detail */ }
    public function edit($id) { /* TODO: FR-05 – form edit */ }
    public function update(Request $request, $id) { /* TODO: FR-05 – update */ }
    public function destroy($id) { /* TODO: FR-05 – hapus */ }
}
