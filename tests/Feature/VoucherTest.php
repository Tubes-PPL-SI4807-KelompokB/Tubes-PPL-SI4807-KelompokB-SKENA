<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        $r = Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]);

        return User::create([
            'role_id' => $r->id, 'name' => ucfirst($role), 'email' => "$role@skena.test", 'password' => 'password123',
        ]);
    }

    private function admin(): User
    {
        return $this->userWithRole('admin');
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'code' => 'promo10', 'type' => 'percent', 'value' => '10',
            'start_date' => '2026-11-01', 'end_date' => '2026-12-31',
            'quota' => '200', 'is_active' => 1,
        ], $override);
    }

    private function make(array $o = []): Voucher
    {
        return Voucher::create(array_merge([
            'code' => 'HEMAT5K', 'type' => 'nominal', 'value' => 5000,
            'start_date' => today()->subDay(), 'end_date' => today()->addDay(),
            'quota' => 100, 'used_count' => 0, 'is_active' => true,
        ], $o));
    }

    /** @test */
    public function tamu_dan_non_admin_tidak_bisa_mengakses(): void
    {
        $this->get(route('admin.vouchers.index'))->assertRedirect(route('login'));
        $this->actingAs($this->userWithRole('client'))->get(route('admin.vouchers.index'))->assertForbidden();
    }

    /** @test */
    public function admin_melihat_daftar_dan_bisa_mencari_serta_memfilter(): void
    {
        $this->make(['code' => 'AKTIF1']);
        $this->make(['code' => 'LEWAT1', 'start_date' => today()->subMonth(), 'end_date' => today()->subDay()]);
        $this->make(['code' => 'NANTI1', 'start_date' => today()->addDay(), 'end_date' => today()->addMonth()]);
        $this->make(['code' => 'MATI1', 'is_active' => false]);

        $this->actingAs($this->admin());

        $this->get(route('admin.vouchers.index'))->assertOk()->assertSee('AKTIF1')->assertSee('LEWAT1')->assertSee('NANTI1')->assertSee('MATI1');
        $this->get(route('admin.vouchers.index', ['status' => 'Aktif']))->assertSee('AKTIF1')->assertDontSee('LEWAT1')->assertDontSee('NANTI1')->assertDontSee('MATI1');
        $this->get(route('admin.vouchers.index', ['status' => 'Kedaluwarsa']))->assertSee('LEWAT1')->assertDontSee('AKTIF1');
        $this->get(route('admin.vouchers.index', ['status' => 'Terjadwal']))->assertSee('NANTI1')->assertDontSee('AKTIF1');
        $this->get(route('admin.vouchers.index', ['status' => 'Nonaktif']))->assertSee('MATI1')->assertDontSee('AKTIF1');
        $this->get(route('admin.vouchers.index', ['q' => 'lewat']))->assertSee('LEWAT1')->assertDontSee('AKTIF1');
    }

    /** @test */
    public function keadaan_kosong_tampil_bila_belum_ada_voucher(): void
    {
        $this->actingAs($this->admin())->get(route('admin.vouchers.index'))->assertOk()->assertSee('Belum ada voucher');
    }

    /** @test */
    public function admin_menambah_voucher_dan_kode_dijadikan_huruf_besar(): void
    {
        $this->actingAs($this->admin())->get(route('admin.vouchers.create'))->assertOk();

        $this->post(route('admin.vouchers.store'), $this->payload())->assertRedirect(route('admin.vouchers.index'));

        $this->assertDatabaseHas('vouchers', ['code' => 'PROMO10', 'type' => 'percent', 'value' => 10, 'quota' => 200, 'used_count' => 0, 'is_active' => 1]);
    }

    /** @test */
    public function nilai_nominal_berformat_titik_dinormalkan(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.vouchers.store'), $this->payload(['code' => 'NOM', 'type' => 'nominal', 'value' => '5.000']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('vouchers', ['code' => 'NOM', 'value' => 5000]);
    }

    /** @test */
    public function validasi_menolak_data_tidak_sah(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.vouchers.store'), [])->assertSessionHasErrors(['code', 'type', 'value', 'start_date', 'end_date', 'quota']);
        $this->post(route('admin.vouchers.store'), $this->payload(['value' => '150']))->assertSessionHasErrors('value');          // persen > 100
        $this->post(route('admin.vouchers.store'), $this->payload(['end_date' => '2026-10-01']))->assertSessionHasErrors('end_date');
        $this->post(route('admin.vouchers.store'), $this->payload(['code' => 'ada spasi!']))->assertSessionHasErrors('code');
        $this->post(route('admin.vouchers.store'), $this->payload(['quota' => '0']))->assertSessionHasErrors('quota');

        $this->assertDatabaseCount('vouchers', 0);
    }

    /** @test */
    public function kode_duplikat_ditolak_dengan_saran_kode(): void
    {
        $this->make(['code' => 'MAHASISWA10']);

        $this->actingAs($this->admin())
            ->post(route('admin.vouchers.store'), $this->payload(['code' => 'mahasiswa10']))
            ->assertSessionHasErrors('code')
            ->assertSessionHas('code_suggestions', fn ($s) => in_array('MAHASISWA15', $s) && ! in_array('MAHASISWA10', $s));

        $this->assertDatabaseCount('vouchers', 1);
    }

    /** @test */
    public function admin_mengubah_voucher_dan_boleh_mempertahankan_kodenya(): void
    {
        $v = $this->make(['code' => 'LAMA']);

        $this->actingAs($this->admin())->get(route('admin.vouchers.edit', $v))->assertOk()->assertSee('LAMA');

        $this->put(route('admin.vouchers.update', $v), $this->payload(['code' => 'LAMA', 'value' => '25', 'is_active' => 0]))
            ->assertRedirect(route('admin.vouchers.index'));

        $this->assertDatabaseHas('vouchers', ['id' => $v->id, 'code' => 'LAMA', 'value' => 25, 'is_active' => 0]);
    }

    /** @test */
    public function kuota_tidak_boleh_di_bawah_jumlah_yang_sudah_dipakai(): void
    {
        $v = $this->make(['quota' => 100, 'used_count' => 60]);

        $this->actingAs($this->admin())
            ->put(route('admin.vouchers.update', $v), $this->payload(['code' => $v->code, 'quota' => '50']))
            ->assertSessionHasErrors('quota');

        $this->assertSame(100, $v->fresh()->quota);
    }

    /** @test */
    public function admin_menghapus_voucher(): void
    {
        $v = $this->make();

        $this->actingAs($this->admin())->delete(route('admin.vouchers.destroy', $v))->assertRedirect(route('admin.vouchers.index'));

        $this->assertDatabaseMissing('vouchers', ['id' => $v->id]);
    }

    /** @test */
    public function non_admin_tidak_bisa_menambah_atau_menghapus(): void
    {
        $v = $this->make();
        $this->actingAs($this->userWithRole('client'));

        $this->post(route('admin.vouchers.store'), $this->payload())->assertForbidden();
        $this->delete(route('admin.vouchers.destroy', $v))->assertForbidden();
        $this->assertDatabaseCount('vouchers', 1);
    }

    /** @test */
    public function status_turunan_sesuai_tanggal_saklar_dan_kuota(): void
    {
        $this->assertSame('Aktif', $this->make(['code' => 'A'])->status);
        $this->assertSame('Terjadwal', $this->make(['code' => 'B', 'start_date' => today()->addDay(), 'end_date' => today()->addDays(5)])->status);
        $this->assertSame('Kedaluwarsa', $this->make(['code' => 'C', 'start_date' => today()->subDays(5), 'end_date' => today()->subDay()])->status);
        $this->assertSame('Nonaktif', $this->make(['code' => 'D', 'is_active' => false])->status);
    }

    /** @test */
    public function dipakai_modul_pesanan_cari_hitung_potongan_dan_redeem(): void
    {
        $this->make(['code' => 'DISKON20', 'type' => 'percent', 'value' => 20, 'quota' => 1]);
        $this->make(['code' => 'BESAR', 'type' => 'nominal', 'value' => 50000]);

        $v = Voucher::findUsable(' diskon20 ');
        $this->assertNotNull($v);
        $this->assertSame(20000, $v->discountFor(100000));
        $this->assertSame(30000, Voucher::findUsable('BESAR')->discountFor(30000));   

        $this->assertTrue($v->redeem());
        $this->assertFalse($v->redeem());                                            
        $this->assertNull(Voucher::findUsable('DISKON20'));
        $this->assertNull(Voucher::findUsable('TIDAKADA'));
    }
}
