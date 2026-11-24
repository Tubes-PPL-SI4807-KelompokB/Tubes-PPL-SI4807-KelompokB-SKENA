<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ServiceCategoryTest – PBI-05 Manajemen Kategori Jasa (FR-11)
 */
class ServiceCategoryTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        $r = Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst($role)]);

        return User::firstOrCreate(
            ['email' => "$role@skena.test"],
            ['role_id' => $r->id, 'name' => ucfirst($role), 'password' => 'password123'],
        );
    }

    private function admin(): User
    {
        return $this->userWithRole('admin');
    }

    #[Test]
    public function non_admin_tidak_bisa_membuka_halaman_kategori(): void
    {
        $this->actingAs($this->userWithRole('freelancer'))
            ->get(route('admin.categories.index'))->assertForbidden();
    }

    #[Test]
    public function admin_melihat_daftar_dengan_jumlah_jasa(): void
    {
        $cat = ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Desain PPT dan poster.']);
        $admin = $this->admin();
        Service::create([
            'provider_id' => $admin->id, 'category_id' => $cat->id, 'judul' => 'PPT', 'deskripsi' => '-',
            'harga' => 20000, 'durasi' => '2 hari', 'status' => 'aktif',
        ]);

        $this->actingAs($admin)->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Kategori Jasa')
            ->assertSee('Desain PPT dan poster.')
            ->assertSeeInOrder(['Desain', '1', 'jasa']);
    }

    #[Test]
    public function keadaan_kosong_tampil_bila_belum_ada_kategori(): void
    {
        $this->actingAs($this->admin())->get(route('admin.categories.index'))
            ->assertOk()->assertSee('Belum ada kategori');
    }

    #[Test]
    public function pencarian_menyaring_kategori(): void
    {
        ServiceCategory::create(['nama_kategori' => 'Desain', 'deskripsi' => 'Poster']);
        ServiceCategory::create(['nama_kategori' => 'Tutor', 'deskripsi' => 'Bimbingan SQL']);

        $this->actingAs($this->admin())->get(route('admin.categories.index', ['q' => 'sql']))
            ->assertSee('Tutor')->assertDontSee('Poster');

        $this->actingAs($this->admin())->get(route('admin.categories.index', ['q' => 'zzz']))
            ->assertSee('Tidak ada kategori yang cocok dengan');
    }

    #[Test]
    public function admin_bisa_menambah_kategori(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), ['nama_kategori' => 'Fotografi', 'deskripsi' => 'Foto produk UMKM.'])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success', 'Kategori "Fotografi" berhasil disimpan');

        $this->assertDatabaseHas('service_categories', ['nama_kategori' => 'Fotografi']);
    }

    #[Test]
    public function nama_kategori_wajib_dan_unik(): void
    {
        ServiceCategory::create(['nama_kategori' => 'Desain']);

        $this->actingAs($this->admin())->post(route('admin.categories.store'), ['nama_kategori' => ''])
            ->assertSessionHasErrors('nama_kategori', null, 'category');

        $this->actingAs($this->admin())->post(route('admin.categories.store'), ['nama_kategori' => 'Desain'])
            ->assertSessionHasErrors('nama_kategori', null, 'category');

        $this->assertDatabaseCount('service_categories', 1);
    }

    #[Test]
    public function admin_bisa_mengubah_kategori(): void
    {
        $cat = ServiceCategory::create(['nama_kategori' => 'Desain']);

        $this->actingAs($this->admin())
            ->put(route('admin.categories.update', $cat), ['nama_kategori' => 'Desain Grafis', 'deskripsi' => 'Logo & poster'])
            ->assertSessionHas('success');

        $this->assertSame('Desain Grafis', $cat->fresh()->nama_kategori);
    }

    #[Test]
    public function ubah_dengan_nama_sama_tidak_dianggap_duplikat(): void
    {
        $cat = ServiceCategory::create(['nama_kategori' => 'Desain']);

        $this->actingAs($this->admin())
            ->put(route('admin.categories.update', $cat), ['nama_kategori' => 'Desain', 'deskripsi' => 'Baru'])
            ->assertSessionHasNoErrors();
    }

    #[Test]
    public function kategori_kosong_bisa_dihapus(): void
    {
        $cat = ServiceCategory::create(['nama_kategori' => 'Lainnya']);

        $this->actingAs($this->admin())->delete(route('admin.categories.destroy', $cat))->assertSessionHas('success');

        $this->assertDatabaseMissing('service_categories', ['id' => $cat->id]);
    }

    #[Test]
    public function kategori_yang_masih_dipakai_jasa_tidak_bisa_dihapus(): void
    {
        $cat = ServiceCategory::create(['nama_kategori' => 'Desain']);
        $admin = $this->admin();
        Service::create([
            'provider_id' => $admin->id, 'category_id' => $cat->id, 'judul' => 'PPT', 'deskripsi' => '-',
            'harga' => 20000, 'durasi' => '2 hari', 'status' => 'aktif',
        ]);

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $cat))->assertSessionHas('error');

        $this->assertDatabaseHas('service_categories', ['id' => $cat->id]);
    }

    #[Test]
    public function non_admin_tidak_bisa_menambah_kategori(): void
    {
        $this->actingAs($this->userWithRole('freelancer'))
            ->post(route('admin.categories.store'), ['nama_kategori' => 'Hack'])->assertForbidden();

        $this->assertDatabaseCount('service_categories', 0);
    }
}
