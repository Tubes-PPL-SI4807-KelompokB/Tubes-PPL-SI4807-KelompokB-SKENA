<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * PaymentTest – PBI-13 Manajemen Pembayaran (FR-19), pembayaran simulasi.
 *
 * Sprint 1: unggah bukti, validasi file, konfirmasi/tolak oleh penyedia, notifikasi,
 * hapus bukti, dan hak akses. Sprint 2 menambah test integrasi (harga negosiasi PBI-12,
 * status pengerjaan PBI-14, penghasilan di dashboard, log admin PBI-03).
 */
class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private User $seeker;
    private User $stranger;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->provider = $this->makeUser('penyedia', 'Nadia Salsabila');
        $this->seeker   = $this->makeUser('pencari', 'Rara Anindya');
        $this->stranger = $this->makeUser('lain', 'Budi Santoso');

        $category = ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Layanan desain']);
        $service  = Service::create([
            'provider_id' => $this->provider->id, 'category_id' => $category->id,
            'judul' => 'Desain PPT Presentasi', 'deskripsi' => 'Jasa desain presentasi',
            'harga' => 20000, 'durasi' => '2 hari', 'status' => 'aktif',
        ]);
        $request = ServiceRequest::create([
            'seeker_id' => $this->seeker->id, 'service_id' => $service->id,
            'kebutuhan' => 'Desain 15 slide sidang.', 'tanggal' => now()->addDays(3)->toDateString(),
            'status' => ServiceRequest::ACCEPTED,
        ]);
        $this->order = Order::create([
            'request_id' => $request->id, 'seeker_id' => $this->seeker->id, 'service_id' => $service->id,
            'harga_final' => 20000, 'voucher_discount' => 2000, 'status' => Order::DIKERJAKAN,
        ]);
    }

    private function makeUser(string $key, string $name): User
    {
        return User::create([
            'name' => $name, 'email' => "{$key}@student.telkomuniversity.ac.id", 'password' => 'password123',
            'email_verified_at' => now(), 'student_verification_status' => 'verified',
        ]);
    }

    private function upload(?User $as = null, ?UploadedFile $file = null)
    {
        return $this->actingAs($as ?? $this->seeker)->post(route('payments.store'), [
            'order_id' => $this->order->id,
            'proof'    => $file ?? UploadedFile::fake()->image('bukti.png'),
        ]);
    }

    private function uploadedPayment(): Payment
    {
        $this->upload()->assertRedirect(route('payments.index'));

        return Payment::firstOrFail();
    }

    // ── Halaman bayar ────────────────────────────────────────

    #[Test]
    public function pencari_bisa_membuka_halaman_bayar_dengan_total_setelah_voucher(): void
    {
        $this->actingAs($this->seeker)->get(route('payments.create', ['order_id' => $this->order->id]))
            ->assertOk()
            ->assertSee($this->order->code)
            ->assertSee('Desain PPT Presentasi')
            ->assertSee('Rp18.000');
    }

    #[Test]
    public function halaman_bayar_pesanan_yang_tidak_ada_diarahkan_ke_daftar_pesanan(): void
    {
        $this->actingAs($this->seeker)->get(route('payments.create', ['order_id' => 999]))
            ->assertRedirect(route('orders.index', ['peran' => 'pencari']))->assertSessionHas('error');
        $this->actingAs($this->seeker)->get(route('payments.create'))
            ->assertRedirect(route('orders.index', ['peran' => 'pencari']));
    }

    // ── Unggah bukti + validasi ──────────────────────────────

    #[Test]
    public function unggah_bukti_valid_menunggu_konfirmasi_dan_memberi_tahu_penyedia(): void
    {
        $payment = $this->uploadedPayment();

        $this->assertSame(Payment::STATUS_MENUNGGU_KONFIRMASI, $payment->status);
        $this->assertSame($this->seeker->id, $payment->payer_id);
        $this->assertSame($this->provider->id, $payment->payee_id);
        $this->assertSame(18000, (int) $payment->amount);
        Storage::disk('public')->assertExists($payment->proof_path);
        $this->assertDatabaseHas('notifications', ['user_id' => $this->provider->id, 'type' => 'pembayaran_masuk']);
    }

    #[Test]
    public function bukti_pdf_diterima(): void
    {
        $this->upload(file: UploadedFile::fake()->create('bukti.pdf', 300, 'application/pdf'))
            ->assertRedirect(route('payments.index'));

        $this->assertSame(1, Payment::count());
    }

    #[Test]
    public function format_dan_ukuran_file_yang_salah_ditolak(): void
    {
        $this->upload(file: UploadedFile::fake()->create('catatan.txt', 5, 'text/plain'))
            ->assertSessionHasErrors(['proof' => 'Format file harus JPG, PNG, atau PDF.']);

        $this->upload(file: UploadedFile::fake()->image('besar.jpg')->size(3000))
            ->assertSessionHasErrors(['proof' => 'Ukuran file maksimal 2 MB.']);

        $this->actingAs($this->seeker)->post(route('payments.store'), ['order_id' => $this->order->id])
            ->assertSessionHasErrors(['proof' => 'File bukti pembayaran wajib diunggah.']);

        $this->assertSame(0, Payment::count());
    }

    // ── Konfirmasi / tolak oleh penyedia ─────────────────────

    #[Test]
    public function penyedia_menolak_bukti_lalu_pencari_unggah_ulang_dan_dikonfirmasi_lunas(): void
    {
        $payment = $this->uploadedPayment();

        $this->actingAs($this->provider)
            ->patch(route('payments.update', $payment), ['action' => 'reject', 'rejection_note' => 'Nominal belum masuk.'])
            ->assertSessionHas('success');
        $this->assertSame(Payment::STATUS_DITOLAK, $payment->fresh()->status);
        $rejected = Notification::where('user_id', $this->seeker->id)->where('type', 'pembayaran_ditolak')->firstOrFail();
        $this->assertStringContainsString('Nominal belum masuk.', $rejected->message);

        // Daftar pembayaran: filter Ditolak + tombol Unggah Ulang.
        $this->actingAs($this->seeker)->get(route('payments.index', ['filter' => 'ditolak']))
            ->assertSee('Unggah Ulang')->assertSee($this->order->code);

        $this->upload()->assertRedirect(route('payments.index'));
        $this->assertSame(Payment::STATUS_MENUNGGU_KONFIRMASI, $payment->fresh()->status);
        $this->assertNull($payment->fresh()->rejection_note);

        $this->actingAs($this->provider)->patch(route('payments.update', $payment), ['action' => 'confirm'])
            ->assertSessionHas('success');
        $this->assertSame(Payment::STATUS_LUNAS, $payment->fresh()->status);
        $this->assertNotNull($payment->fresh()->confirmed_at);
        $this->assertDatabaseHas('notifications', ['user_id' => $this->seeker->id, 'type' => 'pembayaran_lunas']);
    }

    #[Test]
    public function pembayaran_lunas_tidak_bisa_diubah_atau_dibayar_ulang(): void
    {
        $payment = $this->uploadedPayment();
        $this->actingAs($this->provider)->patch(route('payments.update', $payment), ['action' => 'confirm']);

        $this->actingAs($this->provider)->patch(route('payments.update', $payment), ['action' => 'reject'])
            ->assertStatus(422);
        $this->actingAs($this->seeker)->get(route('payments.create', ['order_id' => $this->order->id]))
            ->assertRedirect(route('payments.index'));
        $this->upload()->assertSessionHas('error', 'Pembayaran pesanan ini sudah lunas.');
    }

    // ── Hapus bukti ──────────────────────────────────────────

    #[Test]
    public function pencari_bisa_menghapus_bukti_selama_belum_diproses(): void
    {
        $payment = $this->uploadedPayment();
        $path = $payment->proof_path;

        $this->actingAs($this->seeker)->delete(route('payments.destroy', $payment))
            ->assertRedirect(route('payments.index'))->assertSessionHas('success');

        $this->assertSame(Payment::STATUS_MENUNGGU_PEMBAYARAN, $payment->fresh()->status);
        $this->assertNull($payment->fresh()->proof_path);
        Storage::disk('public')->assertMissing($path);
    }

    // ── Hak akses ────────────────────────────────────────────

    #[Test]
    public function hanya_pencari_pesanan_yang_boleh_mengunggah_bukti(): void
    {
        $this->actingAs($this->provider)->get(route('payments.create', ['order_id' => $this->order->id]))->assertForbidden();
        $this->upload($this->stranger)->assertForbidden();

        $this->assertSame(0, Payment::count());
    }

    #[Test]
    public function hanya_penerima_atau_admin_yang_boleh_mengonfirmasi(): void
    {
        $payment = $this->uploadedPayment();

        $this->actingAs($this->seeker)->patch(route('payments.update', $payment), ['action' => 'confirm'])->assertForbidden();
        $this->actingAs($this->stranger)->patch(route('payments.update', $payment), ['action' => 'confirm'])->assertForbidden();
        $this->assertSame(Payment::STATUS_MENUNGGU_KONFIRMASI, $payment->fresh()->status);

        $admin = $this->makeUser('admin', 'Admin SKENA');
        $admin->update(['role_id' => Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator'])->id]);
        $this->actingAs($admin->fresh())->patch(route('payments.update', $payment), ['action' => 'confirm'])
            ->assertSessionHas('success');
        $this->assertSame(Payment::STATUS_LUNAS, $payment->fresh()->status);
    }

    #[Test]
    public function orang_lain_tidak_bisa_melihat_atau_menghapus_bukti(): void
    {
        $payment = $this->uploadedPayment();

        $this->actingAs($this->stranger)->get(route('payments.show', $payment))->assertForbidden();
        $this->actingAs($this->stranger)->delete(route('payments.destroy', $payment))->assertForbidden();
        $this->actingAs($this->provider)->delete(route('payments.destroy', $payment))->assertForbidden();

        // Riwayat pembayaran hanya menampilkan transaksi milik sendiri.
        $this->actingAs($this->stranger)->get(route('payments.index'))->assertDontSee($this->order->code);
    }

    #[Test]
    public function pembayar_melihat_status_penyedia_melihat_tombol_konfirmasi(): void
    {
        $payment = $this->uploadedPayment();

        $this->actingAs($this->seeker)->get(route('payments.show', $payment))
            ->assertSee('STATUS PEMBAYARAN')->assertDontSee('Konfirmasi Penerimaan');
        $this->actingAs($this->provider)->get(route('payments.show', $payment))
            ->assertSee('KONFIRMASI PEMBAYARAN')->assertSee('Konfirmasi Penerimaan');
    }
}
