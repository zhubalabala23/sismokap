<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Proyek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed users
        $this->artisan('db:seed');
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/proyek');
        $response->assertRedirect('/login');
    }

    public function test_pimpinan_cannot_access_admin_routes()
    {
        $pimpinan = User::where('role', 'pimpinan')->first();

        $response = $this->actingAs($pimpinan)->get('/admin/proyek');
        $response->assertStatus(403);

        $response = $this->actingAs($pimpinan)->get('/admin/user');
        $response->assertStatus(403);

        $response = $this->actingAs($pimpinan)->get('/admin/setting/pengaturan');
        $response->assertStatus(403);
    }

    public function test_pimpinan_cannot_access_export_routes()
    {
        $pimpinan = User::where('role', 'pimpinan')->first();
        $proyek = Proyek::first();

        $response = $this->actingAs($pimpinan)->get(route('laporan.export-pdf', $proyek->id));
        $response->assertStatus(403);

        $response = $this->actingAs($pimpinan)->get(route('laporan.export-excel'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_and_export_routes()
    {
        $admin = User::where('role', 'admin')->first();
        $proyek = Proyek::first();

        $response = $this->actingAs($admin)->get('/admin/proyek');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/setting/pengaturan');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('laporan.export-pdf', $proyek->id));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('laporan.export-excel'));
        $response->assertStatus(200);
    }
}
