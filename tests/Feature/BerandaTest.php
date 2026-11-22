<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * FR-04 – Beranda menjadi halaman awal untuk semua pengunjung.
 */
class BerandaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function tamu_melihat_beranda_di_halaman_awal(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Halo, Mahasiswa!')
            ->assertSee('Daftar Gratis')
            ->assertDontSee('Aktivitas Pesanan Kamu');
    }

    #[Test]
    public function pengguna_login_melihat_beranda_pribadi(): void
    {
        $user = User::create([
            'name'              => 'Earliyanza Putra',
            'email'             => 'earliyanza@student.telkomuniversity.ac.id',
            'password'          => 'password123',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertSee('Halo, Earliyanza!')
            ->assertSee('Aktivitas Pesanan Kamu');
    }

    #[Test]
    public function url_lama_dialihkan_ke_beranda(): void
    {
        $this->get('/beranda')->assertRedirect('/');
        $this->get('/dashboard')->assertRedirect('/');
    }

    #[Test]
    public function katalog_jasa_pindah_ke_jelajahi(): void
    {
        $this->get('/jelajahi')->assertOk()->assertSee('Semua Jasa Mahasiswa');
    }
}
