<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * OrderTest - PBI-11 Manajemen Pesanan.
 */
class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private User $seeker;
    private ServiceCategory $category;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->makeUser('provider', 'Nadia Salsabila');
        $this->seeker = $this->makeUser('seeker', 'Rara Anindya');

        $this->category = ServiceCategory::create([
            'nama_kategori' => 'Desain',
            'deskripsi' => 'Layanan desain',
        ]);

        $this->service = $this->makeService(
            $this->provider,
            'Desain PPT Presentasi'
        );
    }

    /**
     * Membuat user untuk pengujian.
     */
    private function makeUser(string $key, string $name): User
    {
        return User::create([
            'name' => $name,
            'email' => "{$key}@student.telkomuniversity.ac.id",
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Membuat jasa untuk pengujian.
     */
    private function makeService(User $provider, string $title): Service
    {
        return Service::create([
            'provider_id' => $provider->id,
            'category_id' => $this->category->id,
            'judul' => $title,
            'deskripsi' => 'Jasa desain presentasi',
            'harga' => 20000,
            'durasi' => '2 hari',
            'status' => 'aktif',
        ]);
    }

    /**
     * Membuat request jasa.
     */
    private function makeRequest(
        ?Service $service = null,
        ?User $seeker = null,
        string $status = ServiceRequest::PENDING
    ): ServiceRequest {
        return ServiceRequest::create([
            'seeker_id' => ($seeker ?? $this->seeker)->id,
            'service_id' => ($service ?? $this->service)->id,
            'kebutuhan' => 'Desain ulang 15 slide presentasi.',
            'tanggal' => now()->addDays(3)->toDateString(),
            'status' => $status,
        ]);
    }

    #[Test]
    public function penyedia_dapat_melihat_request_masuk_miliknya(): void
    {
        $this->makeRequest();

        $otherProvider = $this->makeUser('other', 'Budi Santoso');
        $otherService = $this->makeService(
            $otherProvider,
            'Desain Logo'
        );

        $this->makeRequest($otherService);

        $this->actingAs($this->provider)
            ->get(route('orders.incoming'))
            ->assertOk()
            ->assertSee('Desain PPT Presentasi')
            ->assertDontSee('Desain Logo');
    }

    #[Test]
    public function penyedia_menerima_request_dan_pesanan_otomatis_dibuat(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)
            ->post(route('requests.accept', $request))
            ->assertRedirect(route('orders.incoming'))
            ->assertSessionHas('success');

        // Status request berubah menjadi accepted.
        $this->assertDatabaseHas('service_requests', [
            'id' => $request->id,
            'status' => ServiceRequest::ACCEPTED,
        ]);

        // Pesanan otomatis berhasil dibuat.
        $this->assertDatabaseHas('orders', [
            'request_id' => $request->id,
            'seeker_id' => $this->seeker->id,
            'service_id' => $this->service->id,
            'status' => 'pending',
        ]);

        // Notifikasi dikirim kepada pencari jasa.
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->seeker->id,
            'type' => 'request_diterima',
        ]);

        $this->assertSame(1, Order::count());
    }

    #[Test]
    public function request_yang_sama_tidak_bisa_membuat_pesanan_ganda(): void
    {
        $request = $this->makeRequest();

        // Penerimaan pertama.
        $this->actingAs($this->provider)
            ->post(route('requests.accept', $request))
            ->assertRedirect(route('orders.incoming'));

        // Penerimaan kedua harus ditolak.
        $this->actingAs($this->provider)
            ->post(route('requests.accept', $request))
            ->assertRedirect(route('orders.incoming'))
            ->assertSessionHas('error');

        $this->assertSame(1, Order::count());

        $this->assertSame(
            1,
            Notification::where('user_id', $this->seeker->id)
                ->where('type', 'request_diterima')
                ->count()
        );
    }

    #[Test]
    public function penyedia_menolak_request_tanpa_membuat_pesanan(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)
            ->post(route('requests.reject', $request))
            ->assertRedirect(route('orders.incoming'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_requests', [
            'id' => $request->id,
            'status' => ServiceRequest::REJECTED,
        ]);

        $this->assertSame(0, Order::count());

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->seeker->id,
            'type' => 'request_ditolak',
        ]);
    }

    #[Test]
    public function penyedia_lain_tidak_bisa_menerima_atau_menolak_request(): void
    {
        $request = $this->makeRequest();

        $otherProvider = $this->makeUser('other', 'Budi Santoso');

        // Penyedia lain mencoba menerima request.
        $this->actingAs($otherProvider)
            ->post(route('requests.accept', $request))
            ->assertForbidden();

        // Penyedia lain mencoba menolak request.
        $this->actingAs($otherProvider)
            ->post(route('requests.reject', $request))
            ->assertForbidden();

        $this->assertSame(
            ServiceRequest::PENDING,
            $request->fresh()->status
        );

        $this->assertSame(0, Order::count());
    }

    #[Test]
    public function pencari_dan_penyedia_dapat_melihat_pesanan(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)
            ->post(route('requests.accept', $request));

        $order = Order::firstOrFail();

        // Pencari jasa dapat melihat daftar dan detail pesanan.
        $this->actingAs($this->seeker)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Desain PPT Presentasi');

        $this->actingAs($this->seeker)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Detail Pesanan');

        // Penyedia jasa juga dapat melihat daftar dan detail pesanan.
        $this->actingAs($this->provider)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Desain PPT Presentasi');

        $this->actingAs($this->provider)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Detail Pesanan');
    }

    #[Test]
    public function pengguna_lain_tidak_dapat_melihat_detail_pesanan(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)
            ->post(route('requests.accept', $request));

        $order = Order::firstOrFail();

        $otherUser = $this->makeUser('other', 'Budi Santoso');

        $this->actingAs($otherUser)
            ->get(route('orders.show', $order))
            ->assertForbidden();
    }
}
