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
 * ServiceTest – PBI-04 Manajemen Jasa (FR-10)
 */
class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private ServiceCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = $this->makeUser('penyedia');
        $this->category = ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Desain PPT & poster']);
    }

    private function makeUser(string $name): User
    {
        return User::create([
            'name' => ucfirst($name), 'email' => "{$name}@student.telkomuniversity.ac.id",
            'password' => 'password123', 'email_verified_at' => now(), 'student_verification_status' => 'verified',
        ]);
    }

    private function makeService(array $o = []): Service
    {
        return Service::create(array_merge([
            'provider_id' => $this->provider->id, 'category_id' => $this->category->id,
            'judul' => 'Desain PPT Sidang', 'deskripsi' => 'Maksimal 15 slide.', 'harga' => 20000,
            'durasi' => '2 hari', 'status' => 'aktif',
        ], $o));
    }

    private function payload(array $o = []): array
    {
        return array_merge([
            'judul' => 'Tutor Python Dasar', 'category_id' => $this->category->id,
            'deskripsi' => 'Belajar Python dari nol.', 'harga' => '40.000', 'durasi' => '1 jam/sesi', 'aktif' => 1,
        ], $o);
    }

    #[Test]
    public function tamu_diarahkan_ke_login(): void
    {
        $this->get(route('services.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function daftar_hanya_menampilkan_jasa_milik_sendiri(): void
    {
        $this->makeService();
        $other = $this->makeUser('lain');
        $this->makeService(['provider_id' => $other->id, 'judul' => 'Jasa Orang Lain']);

        $this->actingAs($this->provider)->get(route('services.index'))
            ->assertOk()
            ->assertSee('Desain PPT Sidang')
            ->assertDontSee('Jasa Orang Lain')
            ->assertSee('1 jasa · 1 aktif, 0 nonaktif.');
    }

    #[Test]
    public function filter_status_dan_pencarian_bekerja(): void
    {
        $this->makeService();
        $this->makeService(['judul' => 'Edit Video Reels', 'status' => 'nonaktif']);

        $this->actingAs($this->provider)->get(route('services.index', ['status' => 'nonaktif']))
            ->assertSee('Edit Video Reels')->assertDontSee('Desain PPT Sidang');

        $this->actingAs($this->provider)->get(route('services.index', ['q' => 'ppt']))
            ->assertSee('Desain PPT Sidang')->assertDontSee('Edit Video Reels');
    }

    #[Test]
    public function keadaan_kosong_tampil_bila_belum_punya_jasa(): void
    {
        $this->actingAs($this->provider)->get(route('services.index'))
            ->assertOk()->assertSee('Kamu belum punya jasa');
    }

    #[Test]
    public function form_tambah_bisa_dibuka(): void
    {
        $this->actingAs($this->provider)->get(route('services.create'))
            ->assertOk()->assertSee('PRATINJAU KARTU')->assertSee('Simpan Jasa');
    }

    #[Test]
    public function penyedia_bisa_menambah_jasa(): void
    {
        $this->actingAs($this->provider)->post(route('services.store'), $this->payload())
            ->assertRedirect(route('services.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'provider_id' => $this->provider->id, 'judul' => 'Tutor Python Dasar',
            'harga' => 40000, 'durasi' => '1 jam/sesi', 'status' => 'aktif',
        ]);
    }

    #[Test]
    public function jasa_bisa_disimpan_sebagai_draf_nonaktif(): void
    {
        $this->actingAs($this->provider)->post(route('services.store'), $this->payload(['aktif' => 0]));

        $this->assertDatabaseHas('services', ['judul' => 'Tutor Python Dasar', 'status' => 'nonaktif']);
    }

    #[Test]
    public function validasi_judul_wajib_dan_harga_minimal(): void
    {
        $this->actingAs($this->provider)->from(route('services.create'))
            ->post(route('services.store'), $this->payload(['judul' => '', 'harga' => '500']))
            ->assertRedirect(route('services.create'))
            ->assertSessionHasErrors(['judul', 'harga']);

        $this->assertDatabaseCount('services', 0);
    }

    #[Test]
    public function penyedia_bisa_mengubah_jasanya(): void
    {
        $s = $this->makeService();

        $this->actingAs($this->provider)->get(route('services.edit', $s))->assertOk()->assertSee('Desain PPT Sidang');

        $this->actingAs($this->provider)->put(route('services.update', $s), $this->payload(['judul' => 'Desain PPT Seminar']))
            ->assertRedirect(route('services.index'));

        $this->assertSame('Desain PPT Seminar', $s->fresh()->judul);
    }

    #[Test]
    public function tidak_bisa_mengelola_jasa_orang_lain(): void
    {
        $s = $this->makeService();
        $other = $this->makeUser('lain');

        $this->actingAs($other)->get(route('services.edit', $s))->assertForbidden();
        $this->actingAs($other)->put(route('services.update', $s), $this->payload())->assertForbidden();
        $this->actingAs($other)->patch(route('services.status', $s))->assertForbidden();
        $this->actingAs($other)->delete(route('services.destroy', $s))->assertForbidden();

        $this->assertDatabaseHas('services', ['id' => $s->id, 'judul' => 'Desain PPT Sidang', 'status' => 'aktif']);
    }

    #[Test]
    public function switch_status_mengaktifkan_dan_menonaktifkan(): void
    {
        $s = $this->makeService();

        $this->actingAs($this->provider)->patch(route('services.status', $s))->assertSessionHas('success');
        $this->assertSame('nonaktif', $s->fresh()->status);

        $this->actingAs($this->provider)->patch(route('services.status', $s));
        $this->assertSame('aktif', $s->fresh()->status);
    }

    #[Test]
    public function penyedia_bisa_menghapus_jasa_tanpa_transaksi(): void
    {
        $s = $this->makeService();

        $this->actingAs($this->provider)->delete(route('services.destroy', $s))
            ->assertRedirect(route('services.index'))->assertSessionHas('success');

        $this->assertDatabaseMissing('services', ['id' => $s->id]);
    }

    #[Test]
    public function jasa_dengan_request_tidak_dihapus_tapi_dinonaktifkan(): void
    {
        $s = $this->makeService();
        $seeker = $this->makeUser('pencari');
        ServiceRequest::create([
            'seeker_id' => $seeker->id, 'service_id' => $s->id,
            'kebutuhan' => 'Butuh 10 slide', 'tanggal' => now()->addWeek()->toDateString(), 'status' => 'menunggu',
        ]);

        $this->actingAs($this->provider)->delete(route('services.destroy', $s))->assertSessionHas('success');

        $this->assertDatabaseHas('services', ['id' => $s->id, 'status' => 'nonaktif']);
    }

    #[Test]
    public function rute_tambah_tidak_tertangkap_rute_detail_publik(): void
    {
        // /services/create tidak boleh tertangkap rute detail publik /services/{id}
        $this->actingAs($this->provider)->get('/services/create')->assertOk()->assertSee('Simpan Jasa');
    }
}
