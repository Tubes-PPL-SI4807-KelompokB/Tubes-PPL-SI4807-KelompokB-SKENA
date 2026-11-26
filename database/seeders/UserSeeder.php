<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $freelancerRole = Role::where('name', 'freelancer')->first();
        $clientRole = Role::where('name', 'client')->first();

        // 1. Akun Admin SKENA
        $admin = User::updateOrCreate(
            ['email' => 'admin@skena.id'],
            [
                'role_id' => $adminRole?->id,
                'name' => 'Admin SKENA',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'status' => 'active',
                'student_verification_status' => 'verified',
                'university' => 'SKENA Headquarters',
                'verified_at' => now(),
            ]
        );
        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'headline' => 'Super Administrator Platform SKENA',
                'bio' => 'Mengawasi ekosistem marketplace kreatif mahasiswa dan menjaga integritas platform.',
                'city' => 'Bandung',
            ]
        );

        // 2. Akun Freelancer Mahasiswa (Terverifikasi) - Earliyanza
        $freelancer1 = User::updateOrCreate(
            ['email' => 'earliyanza@student.telkomuniversity.ac.id'],
            [
                'role_id' => $freelancerRole?->id,
                'name' => 'Earliyanza',
                'password' => Hash::make('password123'),
                'phone' => '085712345678',
                'status' => 'active',
                'student_verification_status' => 'verified',
                'nim' => '1202210001',
                'university' => 'Telkom University',
                'faculty' => 'Fakultas Industri Kreatif',
                'major' => 'Desain Komunikasi Visual',
                'verification_notes' => 'KTM dan KRS aktif valid semester ganjil 2024/2025.',
                'verified_at' => now(),
            ]
        );
        Profile::updateOrCreate(
            ['user_id' => $freelancer1->id],
            [
                'headline' => 'Visual Identity & Brand Designer | Mahasiswa Telkom University',
                'bio' => 'Berpengalaman 3 tahun mengerjakan branding identity, logo design modern, dan layout editorial.',
                'city' => 'Bandung',
                'instagram' => '@earli.design',
                'hourly_rate' => 125000,
            ]
        );

        // 3. Akun Freelancer Mahasiswa (Menunggu Verifikasi KTM) - Anindya
        $freelancer2 = User::updateOrCreate(
            ['email' => 'anindya@student.telkomuniversity.ac.id'],
            [
                'role_id' => $freelancerRole?->id,
                'name' => 'Anindya Putri',
                'password' => Hash::make('password123'),
                'phone' => '082198765432',
                'status' => 'active',
                'student_verification_status' => 'pending',
                'nim' => '1202210002',
                'university' => 'Telkom University',
                'faculty' => 'Fakultas Rekayasa Industri',
                'major' => 'Sistem Informasi',
                'verification_notes' => 'Menunggu peninjauan foto KTM oleh administrator.',
            ]
        );
        Profile::updateOrCreate(
            ['user_id' => $freelancer2->id],
            [
                'headline' => 'UI/UX Designer & Product Researcher',
                'bio' => 'Fokus pada human-centered design, wireframing Figma, dan usability testing.',
                'city' => 'Bandung',
                'hourly_rate' => 150000,
            ]
        );

        // 4. Akun Klien (pencari jasa) Mahasiswa Terverifikasi - Rizal
        //    Batasan proyek: hanya mahasiswa terverifikasi yang boleh bertransaksi.
        $client = User::updateOrCreate(
            ['email' => 'rizal@student.telkomuniversity.ac.id'],
            [
                'role_id' => $clientRole?->id,
                'name' => 'Rizal Kurniawan',
                'password' => Hash::make('password123'),
                'phone' => '081399887766',
                'status' => 'active',
                'student_verification_status' => 'verified',
                'nim' => '1202210003',
                'university' => 'Telkom University',
                'faculty' => 'Fakultas Ekonomi dan Bisnis',
                'major' => 'Manajemen Bisnis',
                'verified_at' => now(),
            ]
        );
        Profile::updateOrCreate(
            ['user_id' => $client->id],
            [
                'headline' => 'Mahasiswa Manajemen Bisnis · Pengurus UKM Kewirausahaan',
                'bio' => 'Sering mencari bantuan desain, video, dan olah data untuk kegiatan UKM.',
                'city' => 'Bandung',
            ]
        );
    }
}
