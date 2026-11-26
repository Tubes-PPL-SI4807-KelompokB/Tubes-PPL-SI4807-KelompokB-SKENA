<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\Voucher;
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
            'student_verification_status' => 'verified',
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

    private function acceptedOrder(): Order
    {
        $request = $this->makeRequest();
        $this->actingAs($this->provider)->post(route('requests.accept', $request));

        return Order::firstOrFail();
    }

    private function makeVoucher(string $code, string $type, int $value, int $quota = 10): Voucher
    {
        return Voucher::create([
            'code' => $code, 'type' => $type, 'value' => $value,
            'start_date' => now()->subDay()->toDateString(), 'end_date' => now()->addMonth()->toDateString(),
            'quota' => $quota, 'used_count' => 0, 'is_active' => true,
        ]);
    }

    #[Test]
    public function penyedia_melihat_pesanan_baru_dari_request_ke_jasanya(): void
    {
        $this->makeRequest();

        $otherProvider = $this->makeUser('other', 'Budi Santoso');
        $this->makeRequest($this->makeService($otherProvider, 'Desain Logo'));

        $this->actingAs($this->provider)
            ->get(route('orders.index', ['peran' => 'penyedia', 'status' => 'baru']))
            ->assertOk()
            ->assertSee('Desain PPT Presentasi')
            ->assertSee('Terima')
            ->assertDontSee('Desain Logo');
    }

    #[Test]
    public function halaman_request_masuk_lama_dialihkan_ke_filter_baru(): void
    {
        $this->actingAs($this->provider)
            ->get(route('orders.incoming'))
            ->assertRedirect(route('orders.index', ['peran' => 'penyedia', 'status' => 'baru']));
    }

    #[Test]
    public function peran_bawaan_mengikuti_kepemilikan_jasa(): void
    {
        $this->makeRequest();

        // Penyedia (punya jasa) langsung melihat "Pesanan Masuk".
        $this->actingAs($this->provider)->get(route('orders.index'))->assertOk()->assertSee('PESANAN MASUK');

        // Pencari (tidak punya jasa) melihat pesanannya sendiri dengan status Menunggu.
        $this->actingAs($this->seeker)->get(route('orders.index'))
            ->assertOk()->assertSee('PESANAN SAYA')->assertSee('Menunggu')->assertDontSee('Terima');
    }

    #[Test]
    public function penyedia_menerima_request_dan_pesanan_otomatis_dibuat(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)
            ->from(route('orders.index'))
            ->post(route('requests.accept', $request))
            ->assertRedirect(route('orders.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_requests', ['id' => $request->id, 'status' => ServiceRequest::ACCEPTED]);

        $this->assertDatabaseHas('orders', [
            'request_id'  => $request->id,
            'seeker_id'   => $this->seeker->id,
            'service_id'  => $this->service->id,
            'status'      => Order::DIKERJAKAN,
            'harga_final' => 20000,
        ]);

        $this->assertDatabaseHas('notifications', ['user_id' => $this->seeker->id, 'type' => 'request_diterima']);
        $this->assertSame(1, Order::count());
    }

    #[Test]
    public function request_yang_sama_tidak_bisa_membuat_pesanan_ganda(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)->post(route('requests.accept', $request));

        $this->actingAs($this->provider)
            ->post(route('requests.accept', $request))
            ->assertSessionHas('error');

        $this->assertSame(1, Order::count());
        $this->assertSame(1, Notification::where('user_id', $this->seeker->id)->where('type', 'request_diterima')->count());
    }

    #[Test]
    public function penyedia_menolak_request_dengan_alasan_tanpa_membuat_pesanan(): void
    {
        $request = $this->makeRequest();
        $alasan = 'Jadwal saya penuh sampai 30 Nov. Silakan ajukan lagi bulan depan.';

        $this->actingAs($this->provider)
            ->post(route('requests.reject', $request), ['alasan_penolakan' => $alasan])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_requests', [
            'id' => $request->id, 'status' => ServiceRequest::REJECTED, 'alasan_penolakan' => $alasan,
        ]);
        $this->assertSame(0, Order::count());

        $notif = Notification::where('user_id', $this->seeker->id)->where('type', 'request_ditolak')->firstOrFail();
        $this->assertStringContainsString($alasan, $notif->message);

        // Kartu "Ditolak" tampil di daftar penyedia beserta alasannya.
        $this->actingAs($this->provider)
            ->get(route('orders.index', ['peran' => 'penyedia', 'status' => 'ditolak']))
            ->assertSee('Ditolak')->assertSee('Jadwal saya penuh');
    }

    #[Test]
    public function tolak_tanpa_alasan_ditolak_validasi(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->provider)
            ->post(route('requests.reject', $request), ['alasan_penolakan' => ''])
            ->assertSessionHasErrors('alasan_penolakan');

        $this->assertSame(ServiceRequest::PENDING, $request->fresh()->status);
    }

    #[Test]
    public function penyedia_lain_tidak_bisa_menerima_atau_menolak_request(): void
    {
        $request = $this->makeRequest();
        $otherProvider = $this->makeUser('other', 'Budi Santoso');

        $this->actingAs($otherProvider)->post(route('requests.accept', $request))->assertForbidden();
        $this->actingAs($otherProvider)
            ->post(route('requests.reject', $request), ['alasan_penolakan' => 'Bukan jasa saya.'])
            ->assertForbidden();

        $this->assertSame(ServiceRequest::PENDING, $request->fresh()->status);
        $this->assertSame(0, Order::count());
    }

    #[Test]
    public function pencari_dan_penyedia_dapat_melihat_pesanan(): void
    {
        $order = $this->acceptedOrder();

        $this->actingAs($this->seeker)->get(route('orders.index'))
            ->assertOk()->assertSee('Desain PPT Presentasi')->assertSee($order->code)->assertSee('Dikerjakan');

        $this->actingAs($this->seeker)->get(route('orders.show', $order))
            ->assertOk()->assertSee('DETAIL PESANAN')->assertSee('Lanjut ke Pembayaran');

        $this->actingAs($this->provider)->get(route('orders.index', ['peran' => 'penyedia']))
            ->assertOk()->assertSee('Desain PPT Presentasi');

        // Penyedia hanya melihat (tidak bisa mengubah catatan/voucher).
        $this->actingAs($this->provider)->get(route('orders.show', $order))
            ->assertOk()->assertSee('DETAIL PESANAN')->assertDontSee('Lanjut ke Pembayaran');
    }

    #[Test]
    public function pengguna_lain_tidak_dapat_melihat_detail_pesanan(): void
    {
        $order = $this->acceptedOrder();
        $otherUser = $this->makeUser('other', 'Budi Santoso');

        $this->actingAs($otherUser)->get(route('orders.show', $order))->assertForbidden();
    }

    #[Test]
    public function pencari_menerapkan_voucher_dan_mengubah_catatan(): void
    {
        $order = $this->acceptedOrder();
        $voucher = $this->makeVoucher('MAHASISWA10', 'percent', 10);

        $this->actingAs($this->seeker)
            ->patch(route('orders.update', $order), ['catatan' => 'Tema biru tua.', 'kode_voucher' => 'mahasiswa10', 'aksi' => 'terapkan'])
            ->assertRedirect(route('orders.show', $order))
            ->assertSessionHas('voucher_msg');

        $order->refresh();
        $this->assertSame($voucher->id, $order->voucher_id);
        $this->assertSame(2000, $order->voucher_discount);
        $this->assertSame(18000, $order->final_price);
        $this->assertSame('Tema biru tua.', $order->catatan);
        $this->assertSame(1, $voucher->fresh()->used_count);

        // Lanjut ke Pembayaran lanjut ke halaman pembayaran PBI-13.
        $this->actingAs($this->seeker)
            ->patch(route('orders.update', $order), ['catatan' => 'Tema biru tua.', 'kode_voucher' => 'MAHASISWA10', 'aksi' => 'simpan'])
            ->assertRedirect(route('payments.create', ['order_id' => $order->id]));

        // Kuota voucher tidak terpakai dua kali untuk kode yang sama.
        $this->assertSame(1, $voucher->fresh()->used_count);

        $this->actingAs($this->seeker)->get(route('payments.create', ['order_id' => $order->id]))
            ->assertOk()->assertSee('Rp18.000')->assertSee('MAHASISWA10')->assertSee('Desain PPT Presentasi');
    }

    #[Test]
    public function mengganti_voucher_mengembalikan_kuota_voucher_lama(): void
    {
        $order = $this->acceptedOrder();
        $old = $this->makeVoucher('MAHASISWA10', 'percent', 10);
        $new = $this->makeVoucher('HEMAT5K', 'nominal', 5000);

        $this->actingAs($this->seeker)->patch(route('orders.update', $order), ['kode_voucher' => 'MAHASISWA10', 'aksi' => 'terapkan']);
        $this->actingAs($this->seeker)->patch(route('orders.update', $order), ['kode_voucher' => 'HEMAT5K', 'aksi' => 'terapkan']);

        $this->assertSame(0, $old->fresh()->used_count);
        $this->assertSame(1, $new->fresh()->used_count);
        $this->assertSame(15000, $order->fresh()->final_price);
    }

    #[Test]
    public function voucher_tidak_valid_menampilkan_pesan_error(): void
    {
        $order = $this->acceptedOrder();

        $this->actingAs($this->seeker)
            ->from(route('orders.show', $order))
            ->patch(route('orders.update', $order), ['kode_voucher' => 'TIDAKADA', 'aksi' => 'terapkan'])
            ->assertRedirect(route('orders.show', $order))
            ->assertSessionHasErrors('kode_voucher');

        $this->assertNull($order->fresh()->voucher_id);
    }

    #[Test]
    public function penyedia_tidak_bisa_mengubah_pesanan(): void
    {
        $order = $this->acceptedOrder();

        $this->actingAs($this->provider)
            ->patch(route('orders.update', $order), ['catatan' => 'Ubah diam-diam'])
            ->assertForbidden();

        $this->assertNull($order->fresh()->catatan);
    }

    #[Test]
    public function daftar_kosong_menampilkan_ajakan_kelola_jasa(): void
    {
        $this->actingAs($this->provider)
            ->get(route('orders.index', ['peran' => 'penyedia']))
            ->assertOk()->assertSee('Belum ada pesanan')->assertSee('Kelola Jasa Saya');
    }
}
