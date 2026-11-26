<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Alur request jasa dari katalog (FR-25) → form request (PBI-10) → tersimpan.
 */
class RequestJasaFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private User $seeker;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->makeUser('penyedia');
        $this->seeker   = $this->makeUser('pencari');

        $category = ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Desain PPT & poster']);

        $this->service = Service::create([
            'provider_id' => $this->provider->id,
            'category_id' => $category->id,
            'judul'       => 'Desain PPT Sidang',
            'deskripsi'   => 'Maksimal 15 slide, revisi 2 kali.',
            'harga'       => 20000,
            'durasi'      => 2,
            'status'      => 'aktif',
        ]);
    }

    private function makeUser(string $name): User
    {
        return User::create([
            'name'              => ucfirst($name),
            'email'             => "{$name}@student.telkomuniversity.ac.id",
            'password'          => 'password123',
            'email_verified_at' => now(),
            'student_verification_status' => 'verified',
        ]);
    }

    #[Test]
    public function katalog_menampilkan_jasa_dari_database_dengan_link_pesan(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Desain PPT Sidang')
            ->assertSee(route('requests.create', ['service_id' => $this->service->id]), false);
    }

    #[Test]
    public function jasa_nonaktif_tidak_tampil_di_katalog(): void
    {
        $this->service->update(['status' => 'nonaktif']);

        $this->get(route('home'))->assertOk()->assertDontSee('Desain PPT Sidang');
    }

    #[Test]
    public function tamu_yang_klik_pesan_diarahkan_ke_login(): void
    {
        $this->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function pencari_bisa_membuka_form_dan_mengirim_request(): void
    {
        $this->actingAs($this->seeker)
            ->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertOk()
            ->assertSee('Desain PPT Sidang')
            ->assertSee('Penyedia');

        $this->actingAs($this->seeker)
            ->post(route('requests.store'), [
                'service_id' => $this->service->id,
                'kebutuhan'  => 'Butuh 12 slide untuk sidang skripsi.',
                'tanggal'    => now()->addDays(3)->toDateString(),
                'catatan'    => 'Tema biru.',
            ])
            ->assertRedirect(route('requests.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_requests', [
            'seeker_id'  => $this->seeker->id,
            'service_id' => $this->service->id,
            'status'     => 'pending',
        ]);

        $this->actingAs($this->seeker)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Desain PPT Sidang');
    }

    #[Test]
    public function penyedia_tidak_bisa_request_jasanya_sendiri(): void
    {
        $this->actingAs($this->provider)
            ->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertRedirect(route('home'));

        $this->actingAs($this->provider)
            ->post(route('requests.store'), [
                'service_id' => $this->service->id,
                'kebutuhan'  => 'Coba request sendiri.',
                'tanggal'    => now()->addDay()->toDateString(),
            ])
            ->assertSessionHas('error');

        $this->assertSame(0, ServiceRequest::count());
    }

    #[Test]
    public function jasa_tidak_ada_atau_nonaktif_ditolak(): void
    {
        $this->actingAs($this->seeker)
            ->get(route('requests.create', ['service_id' => 999]))
            ->assertRedirect(route('home'))
            ->assertSessionHas('error');

        $this->actingAs($this->seeker)
            ->post(route('requests.store'), [
                'service_id' => 999,
                'kebutuhan'  => 'Test',
                'tanggal'    => now()->addDay()->toDateString(),
            ])
            ->assertSessionHasErrors('service_id');
    }

    #[Test]
    public function beranda_menampilkan_rekomendasi_dengan_link_pesan(): void
    {
        $this->actingAs($this->seeker)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Desain PPT Sidang')
            ->assertSee(route('requests.create', ['service_id' => $this->service->id]), false);
    }
}
