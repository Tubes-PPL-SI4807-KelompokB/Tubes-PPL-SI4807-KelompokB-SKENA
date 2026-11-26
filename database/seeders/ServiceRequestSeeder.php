<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * PBI-10 Manajemen Request Jasa — contoh request ke jasa milik Earliyanza (ServiceSeeder)
 * dari Anindya dan Rizal, dengan semua status. Request "accepted" dipakai OrderSeeder.
 */
class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $anin  = User::where('email', 'anindya@student.telkomuniversity.ac.id')->first();
        $rizal = User::where('email', 'rizal@kreatifstudio.com')->first();
        $svc   = Service::pluck('id', 'judul');

        if (! $anin || ! $rizal || $svc->isEmpty()) {
            $this->command?->warn('[ServiceRequestSeeder] User/jasa contoh belum ada – skip.');
            return;
        }

        // [pencari, judul jasa, kebutuhan, +hari tenggat, status, alasan tolak, catatan, hari lalu]
        $rows = [
            [$anin,  'Desain presentasi (PPT)', 'Desain 15 slide sidang skripsi, tema biru tua.', 4, ServiceRequest::ACCEPTED, null, '2 slide diagram alur penelitian.', 6],
            [$rizal, 'Edit video 1 menit',      'Edit video profil UKM durasi 1 menit dengan subtitle.', 2, ServiceRequest::ACCEPTED, null, null, 9],
            [$anin,  'Tutor SQL per jam',       'Tutor SQL pertemuan 2: JOIN dan GROUP BY.', -10, ServiceRequest::ACCEPTED, null, null, 16],
            [$rizal, 'Tutor SQL per jam',       'Tutor SQL pertemuan 3: subquery dan index.', 1, ServiceRequest::ACCEPTED, null, null, 3],
            [$rizal, 'Setup website portofolio', 'Setup website portofolio pribadi pakai template.', 7, ServiceRequest::PENDING, null, 'Domain sudah ada.', 0],
            [$anin,  'Bantuan Excel dasar',     'Olah data kuesioner 120 responden di Excel.', 5, ServiceRequest::PENDING, null, null, 0],
            [$rizal, 'Foto produk sederhana',   'Foto 10 produk UMKM dengan latar putih.', 3, ServiceRequest::REJECTED, 'Jadwal saya penuh sampai akhir bulan. Silakan ajukan lagi bulan depan.', null, 18],
            [$anin,  'Foto produk sederhana',   'Foto 5 produk kerajinan tangan.', 6, ServiceRequest::CANCELLED, null, null, 12],
        ];

        foreach ($rows as [$seeker, $judul, $kebutuhan, $due, $status, $alasan, $catatan, $ago]) {
            if (! $svc->has($judul)) {
                continue;
            }
            $at = now()->subDays($ago)->subMinutes(count($rows));

            $req = ServiceRequest::updateOrCreate(
                ['seeker_id' => $seeker->id, 'service_id' => $svc[$judul], 'kebutuhan' => $kebutuhan],
                ['tanggal' => now()->addDays($due)->toDateString(), 'catatan' => $catatan, 'status' => $status, 'alasan_penolakan' => $alasan]
            );
            $req->timestamps = false;
            $req->forceFill(['created_at' => $at, 'updated_at' => $at])->save();
        }

        $this->command?->info('[ServiceRequestSeeder] ' . ServiceRequest::count() . ' request jasa di-seed.');
    }
}
