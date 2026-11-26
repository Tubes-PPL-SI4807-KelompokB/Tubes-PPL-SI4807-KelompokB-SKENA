<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Perbaikan hasil uji coba Sprint 1: verifikasi mahasiswa, notifikasi,
 * request ganda, pesanan, voucher, dan pembayaran (PBI-10, 11, 13, 17, FR-02, FR-04).
 */
class Sprint1AlurTransaksiTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private User $seeker;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->makeUser('penyedia', 'Nadia Salsabila');
        $this->seeker   = $this->makeUser('pencari', 'Rara Anindya');

        $category = ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Layanan desain']);
        $this->service = Service::create([
            'provider_id' => $this->provider->id,
            'category_id' => $category->id,
            'judul'       => 'Desain PPT Presentasi',
            'deskripsi'   => 'Jasa desain presentasi',
            'harga'       => 20000,
            'durasi'      => '2 hari',
            'status'      => 'aktif',
        ]);
    }

    private function makeUser(string $key, string $name, string $status = 'verified'): User
    {
        return User::create([
            'name'                        => $name,
            'email'                       => "{$key}@student.telkomuniversity.ac.id",
            'password'                    => 'password123',
            'nim'                         => '12022' . random_int(10000, 99999),
            'email_verified_at'           => now(),
            'student_verification_status' => $status,
        ]);
    }

    private function makeRequest(array $attrs = []): ServiceRequest
    {
        return ServiceRequest::create(array_merge([
            'seeker_id'  => $this->seeker->id,
            'service_id' => $this->service->id,
            'kebutuhan'  => 'Desain ulang 15 slide sidang.',
            'tanggal'    => now()->addDays(3)->toDateString(),
            'catatan'    => 'Tema biru tua.',
            'status'     => ServiceRequest::PENDING,
        ], $attrs));
    }

    private function acceptedOrder(): Order
    {
        $this->actingAs($this->provider)->post(route('requests.accept', $this->makeRequest()));

        return Order::firstOrFail();
    }

    private function uploadProof(Order $order): Payment
    {
        Storage::fake('public');
        $this->actingAs($this->seeker)->post(route('payments.store'), [
            'order_id' => $order->id,
            'proof'    => UploadedFile::fake()->image('bukti.png'),
        ])->assertRedirect(route('payments.index'));

        return Payment::firstOrFail();
    }

    // ── Verifikasi mahasiswa (FR-02) ─────────────────────────

    #[Test]
    public function akun_belum_terverifikasi_tidak_bisa_memesan_atau_menawarkan_jasa(): void
    {
        $unverified = $this->makeUser('belum', 'Belum Verifikasi', 'unverified');
        $pending    = $this->makeUser('pending', 'Menunggu Admin', 'pending');

        $this->actingAs($unverified)->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertRedirect(route('verification.notice'));
        $this->actingAs($pending)->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->actingAs($pending)->get(route('services.create'))->assertRedirect(route('dashboard'));
        $this->actingAs($pending)->post(route('requests.store'), [
            'service_id' => $this->service->id, 'kebutuhan' => 'Coba pesan', 'tanggal' => now()->addDay()->toDateString(),
        ])->assertSessionHas('error');

        $this->assertSame(0, ServiceRequest::count());

        // Halaman lihat-saja tetap bisa dibuka.
        $this->actingAs($pending)->get(route('orders.index'))->assertOk();
    }

    #[Test]
    public function akun_terverifikasi_bisa_membuka_form_request(): void
    {
        $this->actingAs($this->seeker)->get(route('requests.create', ['service_id' => $this->service->id]))->assertOk();
    }

    // ── Request jasa (PBI-10) ────────────────────────────────

    #[Test]
    public function request_ganda_untuk_jasa_yang_sama_ditolak(): void
    {
        $this->makeRequest();

        $this->actingAs($this->seeker)->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertRedirect(route('requests.index', ['status' => ServiceRequest::PENDING]))
            ->assertSessionHas('error');

        $this->actingAs($this->seeker)->post(route('requests.store'), [
            'service_id' => $this->service->id, 'kebutuhan' => 'Request kedua', 'tanggal' => now()->addDay()->toDateString(),
        ])->assertSessionHas('error');

        $this->assertSame(1, ServiceRequest::count());
    }

    #[Test]
    public function jasa_nonaktif_diarahkan_dengan_pesan_bukan_404(): void
    {
        $this->service->update(['status' => 'nonaktif']);

        $this->actingAs($this->seeker)->get(route('requests.create', ['service_id' => $this->service->id]))
            ->assertRedirect(route('home'))
            ->assertSessionHas('error', 'Jasa ini sedang tidak tersedia. Pilih jasa lain di halaman Jelajahi Jasa.');
    }

    // ── Pesanan (PBI-11) ─────────────────────────────────────

    #[Test]
    public function kartu_baru_menampilkan_isi_permintaan_dan_kartu_pesanan_status_bayar(): void
    {
        $this->makeRequest();

        $this->actingAs($this->provider)->get(route('orders.index', ['peran' => 'penyedia']))
            ->assertSee('Desain ulang 15 slide sidang.')
            ->assertSee('Tema biru tua.');

        $order = $this->acceptedOrder();
        $this->actingAs($this->seeker)->get(route('orders.index', ['peran' => 'pencari']))
            ->assertSee($order->code)->assertSee('Belum dibayar');
    }

    #[Test]
    public function pesan_voucher_menjelaskan_penyebabnya(): void
    {
        $order = $this->acceptedOrder();
        Voucher::create([
            'code' => 'LAMA10', 'type' => 'percent', 'value' => 10,
            'start_date' => now()->subMonth()->toDateString(), 'end_date' => now()->subDay()->toDateString(),
            'quota' => 10, 'used_count' => 0, 'is_active' => true,
        ]);

        $this->actingAs($this->seeker)->patch(route('orders.update', $order), ['kode_voucher' => 'LAMA10', 'aksi' => 'terapkan'])
            ->assertSessionHasErrors(['kode_voucher' => 'Voucher LAMA10 sudah kedaluwarsa sejak ' . now()->subDay()->translatedFormat('j M Y') . '. Coba kode lain.']);

        $this->actingAs($this->seeker)->patch(route('orders.update', $order), ['kode_voucher' => 'NGASAL', 'aksi' => 'terapkan'])
            ->assertSessionHasErrors(['kode_voucher' => 'Kode voucher NGASAL tidak ditemukan. Periksa lagi penulisannya.']);
    }

    // ── Pembayaran + notifikasi (PBI-13, PBI-17) ─────────────

    #[Test]
    public function halaman_bayar_pesanan_yang_tidak_ada_diarahkan_ke_daftar_pesanan(): void
    {
        $this->actingAs($this->seeker)->get(route('payments.create', ['order_id' => 999]))
            ->assertRedirect(route('orders.index', ['peran' => 'pencari']))->assertSessionHas('error');
        $this->actingAs($this->seeker)->get(route('payments.create'))
            ->assertRedirect(route('orders.index', ['peran' => 'pencari']));
    }

    #[Test]
    public function unggah_tolak_dan_konfirmasi_bukti_mengirim_notifikasi(): void
    {
        $order   = $this->acceptedOrder();
        $payment = $this->uploadProof($order);

        $this->assertSame(Payment::STATUS_MENUNGGU_KONFIRMASI, $payment->status);
        $this->assertDatabaseHas('notifications', ['user_id' => $this->provider->id, 'type' => 'pembayaran_masuk']);

        $this->actingAs($this->provider)
            ->patch(route('payments.update', $payment), ['action' => 'reject', 'rejection_note' => 'Nominal belum masuk.'])
            ->assertSessionHas('success');
        $rejected = Notification::where('user_id', $this->seeker->id)->where('type', 'pembayaran_ditolak')->firstOrFail();
        $this->assertStringContainsString('Nominal belum masuk.', $rejected->message);

        // Daftar pembayaran punya filter Ditolak + tombol unggah ulang.
        $this->actingAs($this->seeker)->get(route('payments.index', ['filter' => 'ditolak']))
            ->assertSee('Unggah Ulang')->assertSee($order->code);

        $payment = $this->uploadProof($order);
        $this->actingAs($this->provider)->patch(route('payments.update', $payment), ['action' => 'confirm'])
            ->assertSessionHas('success');

        $this->assertSame(Payment::STATUS_LUNAS, $payment->fresh()->status);
        $this->assertDatabaseHas('notifications', ['user_id' => $this->seeker->id, 'type' => 'pembayaran_lunas']);
    }

    #[Test]
    public function pembayar_melihat_status_bukan_konfirmasi(): void
    {
        $payment = $this->uploadProof($this->acceptedOrder());

        $this->actingAs($this->seeker)->get(route('payments.show', $payment))
            ->assertSee('STATUS PEMBAYARAN')->assertDontSee('Konfirmasi Penerimaan');
        $this->actingAs($this->provider)->get(route('payments.show', $payment))
            ->assertSee('KONFIRMASI PEMBAYARAN')->assertSee('Konfirmasi Penerimaan');
    }

    // ── Notifikasi lonceng (PBI-17) ──────────────────────────

    #[Test]
    public function lonceng_menampilkan_jumlah_dan_isi_notifikasi_belum_dibaca(): void
    {
        $this->makeRequest();
        Notification::create(['user_id' => $this->provider->id, 'type' => 'request_baru', 'title' => 'Request jasa baru',
            'message' => 'Rara mengajukan request.', 'data' => ['service_request_id' => 1], 'is_read' => false]);

        $this->actingAs($this->provider)->get(route('dashboard'))
            ->assertSee('1 belum dibaca')
            ->assertSee('Rara mengajukan request.');
    }

    #[Test]
    public function tombol_lihat_notifikasi_menandai_dibaca_lalu_membuka_halaman_terkait(): void
    {
        $order = $this->acceptedOrder();
        $notif = Notification::where('user_id', $this->seeker->id)->where('type', 'request_diterima')->firstOrFail();

        $this->actingAs($this->seeker)->patch(route('notifications.update', $notif), ['buka' => 1])
            ->assertRedirect(route('orders.show', $order));

        $this->assertTrue($notif->fresh()->is_read);
    }

    // ── Beranda, login, halaman error ────────────────────────

    #[Test]
    public function chip_kategori_beranda_memakai_slug_jelajahi(): void
    {
        $this->get(route('dashboard'))->assertSee(route('home', ['kategori' => 'desain']), false);
    }

    #[Test]
    public function tamu_yang_diarahkan_ke_login_tidak_melihat_pesan_sesi_berakhir(): void
    {
        $this->get(route('requests.create', ['service_id' => $this->service->id]))->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertSee('Masuk dulu untuk melanjutkan')
            ->assertDontSee('Sesi berakhir');
    }

    #[Test]
    public function halaman_404_berbahasa_indonesia(): void
    {
        $this->actingAs($this->seeker)->get(route('orders.show', 9999))
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan')
            ->assertSee('Ke Beranda');
    }
}
