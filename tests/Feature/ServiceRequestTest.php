<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ServiceRequestTest – PBI-10 Manajemen Request Jasa (daftar, ubah, batal, notifikasi).
 */
class ServiceRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private User $seeker;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->makeUser('nadia', 'Nadia Salsabila');
        $this->seeker   = $this->makeUser('rara', 'Rara Anindya');

        $category = ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Desain PPT']);

        $this->service = Service::create([
            'provider_id' => $this->provider->id,
            'category_id' => $category->id,
            'judul'       => 'Desain PPT Presentasi Sidang',
            'deskripsi'   => '15 slide.',
            'harga'       => 20000,
            'durasi'      => '2 hari',
            'status'      => 'aktif',
        ]);
    }

    private function makeUser(string $key, string $name): User
    {
        return User::create([
            'name'              => $name,
            'email'             => "{$key}@student.telkomuniversity.ac.id",
            'password'          => 'password123',
            'email_verified_at' => now(),
        ]);
    }

    private function makeRequest(string $status = ServiceRequest::PENDING, ?User $seeker = null): ServiceRequest
    {
        return ServiceRequest::create([
            'seeker_id'  => ($seeker ?? $this->seeker)->id,
            'service_id' => $this->service->id,
            'kebutuhan'  => 'Desain ulang 15 slide sidang, tema biru tua.',
            'tanggal'    => now()->addDays(3)->toDateString(),
            'status'     => $status,
        ]);
    }

    #[Test]
    public function daftar_menampilkan_request_milik_sendiri_dengan_penyedia(): void
    {
        $this->makeRequest();
        $other = $this->makeUser('lain', 'Orang Lain');
        ServiceRequest::create([
            'seeker_id' => $other->id, 'service_id' => $this->service->id,
            'kebutuhan' => 'Rahasia orang lain', 'tanggal' => now()->toDateString(), 'status' => 'pending',
        ]);

        $this->actingAs($this->seeker)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Desain PPT Presentasi Sidang')
            ->assertSee('Penyedia: Nadia Salsabila')
            ->assertSee('Menunggu')
            ->assertDontSee('Rahasia orang lain');
    }

    #[Test]
    public function filter_status_hanya_menampilkan_status_terpilih(): void
    {
        $this->makeRequest(ServiceRequest::CANCELLED);

        $this->actingAs($this->seeker)->get(route('requests.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Tidak ada request yang cocok');

        $this->actingAs($this->seeker)->get(route('requests.index', ['status' => 'cancelled']))
            ->assertOk()
            ->assertSee('Kamu membatalkan pada');
    }

    #[Test]
    public function keadaan_kosong_mengajak_cari_jasa(): void
    {
        $this->actingAs($this->seeker)->get(route('requests.index'))
            ->assertOk()
            ->assertSee('Belum ada request')
            ->assertSee(route('home'), false);
    }

    #[Test]
    public function kirim_request_memberi_notifikasi_ke_penyedia(): void
    {
        $this->actingAs($this->seeker)->post(route('requests.store'), [
            'service_id' => $this->service->id,
            'kebutuhan'  => 'Poster A3 seminar.',
            'tanggal'    => now()->addDay()->toDateString(),
        ])->assertRedirect(route('requests.index'))
          ->assertSessionHas('success', 'Request terkirim, penyedia akan diberi tahu');

        $this->assertDatabaseHas('notifications', ['user_id' => $this->provider->id, 'type' => 'request_baru']);
    }

    #[Test]
    public function validasi_kebutuhan_dan_tanggal(): void
    {
        $this->actingAs($this->seeker)->post(route('requests.store'), [
            'service_id' => $this->service->id,
            'kebutuhan'  => '',
            'tanggal'    => now()->subDay()->toDateString(),
        ])->assertSessionHasErrors(['kebutuhan', 'tanggal']);

        $this->assertSame(0, ServiceRequest::count());
    }

    #[Test]
    public function request_menunggu_bisa_diubah_pemiliknya(): void
    {
        $item = $this->makeRequest();

        $this->actingAs($this->seeker)->get(route('requests.edit', $item))
            ->assertOk()->assertSee('Simpan Perubahan')->assertSee('Desain PPT Presentasi Sidang');

        $this->actingAs($this->seeker)->put(route('requests.update', $item), [
            'kebutuhan' => 'Jadi 20 slide.',
            'tanggal'   => now()->addDays(5)->toDateString(),
            'catatan'   => 'Draf di Drive',
        ])->assertRedirect(route('requests.index'));

        $this->assertSame('Jadi 20 slide.', $item->fresh()->kebutuhan);
    }

    #[Test]
    public function request_yang_sudah_diproses_atau_milik_orang_lain_tidak_bisa_diubah(): void
    {
        $accepted = $this->makeRequest(ServiceRequest::ACCEPTED);
        $this->actingAs($this->seeker)->get(route('requests.edit', $accepted))->assertNotFound();

        $mine = $this->makeRequest();
        $other = $this->makeUser('lain', 'Orang Lain');
        $this->actingAs($other)->put(route('requests.update', $mine), [
            'kebutuhan' => 'Diubah orang lain', 'tanggal' => now()->toDateString(),
        ])->assertNotFound();
        $this->actingAs($other)->delete(route('requests.destroy', $mine))->assertNotFound();

        $this->assertSame(ServiceRequest::PENDING, $mine->fresh()->status);
    }

    #[Test]
    public function batalkan_request_mengubah_status_dan_memberi_tahu_penyedia(): void
    {
        $item = $this->makeRequest();

        $this->actingAs($this->seeker)->delete(route('requests.destroy', $item))
            ->assertRedirect(route('requests.index'))
            ->assertSessionHas('success');

        $this->assertSame(ServiceRequest::CANCELLED, $item->fresh()->status);
        $this->assertDatabaseHas('service_requests', ['id' => $item->id]); // riwayat tidak dihapus
        $this->assertSame(1, Notification::where('user_id', $this->provider->id)->where('type', 'request_dibatalkan')->count());
    }
}
