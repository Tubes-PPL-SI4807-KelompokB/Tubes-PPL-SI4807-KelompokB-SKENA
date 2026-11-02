<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ServiceCategoryController – PBI-05
 * PIC: Anindya
 */
class ServiceCategoryController extends Controller
{
    public function index()   { /* TODO: PBI-05 – daftar */ return view('admin.'.strtolower(str_replace('Controller','',str_replace('Admin','',' ServiceCategoryController'))).'.index'); }
    public function create()  { /* TODO: PBI-05 – form tambah */ }
    public function store(Request $request)  { /* TODO: PBI-05 – simpan */ }
    public function show($id) { /* TODO: PBI-05 – detail */ }
    public function edit($id) { /* TODO: PBI-05 – form edit */ }
    public function update(Request $request, $id) { /* TODO: PBI-05 – update */ }
    public function destroy($id) { /* TODO: PBI-05 – hapus */ }
}
