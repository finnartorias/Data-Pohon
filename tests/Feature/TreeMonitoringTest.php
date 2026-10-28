<?php

namespace Tests\Feature;

use App\Models\Tree;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TreeMonitoringTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test umur accessor calculates automatically based on tanggal_tanam.
     */
    public function test_tree_age_accessor_calculates_correctly(): void
    {
        $tree = new Tree([
            'id_pohon' => 'TEST-001',
            'nama_pohon' => 'Pohon Uji',
            'tanggal_tanam' => Carbon::now()->subYears(2)->subMonths(3),
        ]);

        $this->assertEquals('2 tahun 3 bulan', $tree->umur);
    }

    /**
     * Test automatic QR Code generation when creating a tree.
     */
    public function test_qr_code_is_generated_automatically_on_create(): void
    {
        Storage::fake('public');

        $tree = Tree::create([
            'id_pohon' => 'TREE-TEST-99',
            'nama_pohon' => 'Pohon Flamboyan',
            'nama_latin' => 'Delonix regia',
            'tanggal_tanam' => '2023-01-15',
            'kordinat_lat' => -6.1753924,
            'kordinat_lng' => 106.8271528,
            'estimasi_karbon' => 60.50,
            'lokasi_taman' => 'Taman Kota',
        ]);

        $this->assertNotEmpty($tree->qr_code_path);
        Storage::disk('public')->assertExists($tree->qr_code_path);

        $tree->delete();
    }

    /**
     * Test public page access without authentication.
     */
    public function test_public_can_view_tree_details_via_qr_url(): void
    {
        $tree = Tree::firstOrCreate([
            'id_pohon' => 'PUB-001',
        ], [
            'nama_pohon' => 'Pohon Peneduh Monas',
            'nama_latin' => 'Ficus elastica',
            'tanggal_tanam' => '2022-05-10',
            'kordinat_lat' => -6.175,
            'kordinat_lng' => 106.827,
            'estimasi_karbon' => 120.0,
            'lokasi_taman' => 'Monas Timur',
        ]);

        $response = $this->get('/pohon/public/' . $tree->id_pohon);

        $response->assertStatus(200);
        $response->assertSee('Pohon Peneduh Monas');
        $response->assertSee('120');
        $response->assertSee($tree->umur);
    }

    /**
     * Test that admin dashboard requires authentication.
     */
    public function test_admin_dashboard_redirects_unauthenticated_users(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated admin can view dashboard and trees.
     */
    public function test_authenticated_admin_can_access_dashboard_and_trees(): void
    {
        $admin = User::first() ?? User::factory()->create();

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Monitoring Pohon');

        $responseIndex = $this->actingAs($admin)->get('/trees');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Data Pohon');
    }

    /**
     * Test QR Print and Download endpoints.
     */
    public function test_admin_can_view_print_qr_template(): void
    {
        $admin = User::first() ?? User::factory()->create();
        $tree = Tree::create([
            'id_pohon' => 'PRINT-001',
            'nama_pohon' => 'Pohon Akasia',
            'tanggal_tanam' => '2023-01-01',
            'kordinat_lat' => -6.175,
            'kordinat_lng' => 106.827,
            'estimasi_karbon' => 50,
        ]);

        $response = $this->actingAs($admin)->get(route('trees.print-qr', $tree));
        $response->assertStatus(200);
        $response->assertSee($tree->id_pohon);
        $response->assertSee('SCAN KODE QR DI ATAS');
    }

    /**
     * Test admin can perform full CRUD lifecycle on a tree.
     */
    public function test_admin_can_store_update_and_delete_tree(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        // 1. Store
        $response = $this->actingAs($admin)->post('/trees', [
            'id_pohon' => 'TREE-CRUD-01',
            'nama_pohon' => 'Pohon Asam Jawa',
            'nama_latin' => 'Tamarindus indica',
            'tanggal_tanam' => '2021-06-01',
            'kordinat_lat' => -6.180,
            'kordinat_lng' => 106.830,
            'estimasi_karbon' => 88.0,
            'lokasi_taman' => 'Taman Suropati',
            'deskripsi' => 'Pohon rindang peneduh jalan.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('trees', ['id_pohon' => 'TREE-CRUD-01']);

        $tree = Tree::where('id_pohon', 'TREE-CRUD-01')->first();
        $this->assertNotNull($tree->qr_code_path);
        Storage::disk('public')->assertExists($tree->qr_code_path);

        // 2. Update
        $responseUpdate = $this->actingAs($admin)->put('/trees/' . $tree->id, [
            'id_pohon' => 'TREE-CRUD-01',
            'nama_pohon' => 'Pohon Asam Jawa Super',
            'nama_latin' => 'Tamarindus indica',
            'tanggal_tanam' => '2021-06-01',
            'kordinat_lat' => -6.180,
            'kordinat_lng' => 106.830,
            'estimasi_karbon' => 95.0,
            'lokasi_taman' => 'Taman Suropati Blok B',
        ]);

        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('trees', ['nama_pohon' => 'Pohon Asam Jawa Super']);

        // 3. Delete
        $qrPath = $tree->qr_code_path;
        $responseDelete = $this->actingAs($admin)->delete('/trees/' . $tree->id);
        $responseDelete->assertRedirect('/trees');

        $this->assertDatabaseMissing('trees', ['id' => $tree->id]);
        Storage::disk('public')->assertMissing($qrPath);
    }
}
