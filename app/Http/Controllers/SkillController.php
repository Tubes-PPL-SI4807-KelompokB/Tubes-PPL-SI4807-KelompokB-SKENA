<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * SkillController – PBI-02
 * PIC: Earliyanza
 */
class SkillController extends Controller
{
    /** PBI-02 – daftar */
    public function index() {}

    /** PBI-02 – form tambah */
    public function create() {}

    /** PBI-02 – simpan */
    public function store(Request $request) {}

    /** PBI-02 – detail */
    public function show($id) {}

    /** PBI-02 – form edit */
    public function edit($id) {}

    /** PBI-02 – update */
    public function update(Request $request, $id) {}

    /** PBI-02 – hapus */
    public function destroy($id) {}
}
