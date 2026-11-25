<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FixTrackFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $reporter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admintest@example.com',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->reporter = User::create([
            'name' => 'Karyawan IT',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('UserPass123!'),
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_login_page_has_no_demo_accounts(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('FixTrack');
        $response->assertDontSee('Akses Cepat Akun Demo');
        $response->assertDontSee('admin@fixtrack.com');
        $response->assertDontSee('user@fixtrack.com');
    }

    public function test_admin_and_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'AdminPass123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);

        $this->post('/logout');

        $userResponse = $this->post('/login', [
            'email' => $this->reporter->email,
            'password' => 'UserPass123!',
        ]);

        $userResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->reporter);
    }

    public function test_database_starts_clean_with_it_categories_and_zero_reports(): void
    {
        $this->assertEquals(0, Report::count());
        $this->assertDatabaseHas('categories', ['name' => 'Hardware Komputer']);
        $this->assertDatabaseHas('categories', ['name' => 'Network & Infrastructure']);
        $this->assertDatabaseHas('categories', ['name' => 'Storage & Peripheral']);
        $this->assertDatabaseHas('categories', ['name' => 'Software & Lisensi']);

        $userDash = $this->actingAs($this->reporter)->get('/dashboard');
        $userDash->assertStatus(200);
        $userDash->assertSee('Belum ada laporan');
        $userDash->assertSee('Buat Laporan Sekarang');

        $adminDash = $this->actingAs($this->admin)->get('/dashboard');
        $adminDash->assertStatus(200);
        $adminDash->assertSee('Belum ada laporan');
    }

    public function test_user_cannot_access_admin_routes(): void
    {
        $this->actingAs($this->reporter)->get('/users')->assertStatus(403);
        $this->actingAs($this->reporter)->get('/categories')->assertStatus(403);
        $this->actingAs($this->reporter)->get('/items')->assertStatus(403);
        $this->actingAs($this->reporter)->get('/exports')->assertStatus(403);
    }

    public function test_user_can_create_it_report_with_inventory_code(): void
    {
        Storage::fake('public');
        $category = Category::where('name', 'Network & Infrastructure')->first();

        $response = $this->actingAs($this->reporter)->post('/reports', [
            'item_name' => 'Managed Switch 24-Port Gigabit',
            'inventory_code' => 'IT-NW-002',
            'category_id' => $category->id,
            'location' => 'Rack Server Lantai 2',
            'problem_type' => 'Koneksi Jaringan Bermasalah',
            'description' => 'Port 5 sampai 8 tidak menyala dan koneksi VLAN terputus.',
            'quantity' => 1,
            'priority' => 'Tinggi',
            'photo' => UploadedFile::fake()->image('switch_error.jpg'),
        ]);

        $this->assertDatabaseHas('reports', [
            'item_name' => 'Managed Switch 24-Port Gigabit',
            'inventory_code' => 'IT-NW-002',
            'problem_type' => 'Koneksi Jaringan Bermasalah',
            'priority' => 'Tinggi',
            'status' => 'Pending',
        ]);

        $createdReport = Report::where('inventory_code', 'IT-NW-002')->first();
        $response->assertRedirect(route('reports.show', $createdReport));

        $this->assertDatabaseHas('report_histories', [
            'report_id' => $createdReport->id,
            'action' => 'Pembuatan Laporan',
            'new_status' => 'Pending',
        ]);
    }

    public function test_user_cannot_view_other_users_report(): void
    {
        $otherUser = User::create([
            'name' => 'User Lain',
            'email' => 'other@example.com',
            'password' => Hash::make('OtherPass123!'),
            'role' => 'user',
            'status' => 'active',
        ]);

        $category = Category::first();
        $report = Report::create([
            'ticket_number' => 'TKT-20261125-0001',
            'user_id' => $otherUser->id,
            'item_name' => 'Laptop Bisnis',
            'category_id' => $category->id,
            'location' => 'Divisi HR',
            'problem_type' => 'Perangkat Tidak Berfungsi',
            'description' => 'Laptop tidak bisa menyala saat tombol power ditekan.',
            'quantity' => 1,
            'priority' => 'Sedang',
            'status' => 'Pending',
        ]);

        $this->actingAs($this->reporter)
            ->get(route('reports.show', $report))
            ->assertStatus(403);
    }

    public function test_admin_can_update_report_status_upload_completion_photo_and_delete(): void
    {
        Storage::fake('public');
        $category = Category::first();

        $report = Report::create([
            'ticket_number' => 'TKT-20261125-0002',
            'user_id' => $this->reporter->id,
            'item_name' => 'Desktop PC Workstation',
            'inventory_code' => 'IT-HW-001',
            'category_id' => $category->id,
            'location' => 'Divisi Finance',
            'problem_type' => 'Kerusakan Hardware',
            'description' => 'Power supply mengeluarkan bunyi dan komputer mati mendadak.',
            'quantity' => 1,
            'priority' => 'Tinggi',
            'status' => 'Pending',
        ]);

        // Update to Sedang Diproses
        $this->actingAs($this->admin)->patch(route('reports.update-status', $report), [
            'status' => 'Sedang Diproses',
            'priority' => 'Tinggi',
            'handling_notes' => 'Sedang dilakukan pengecekan PSU oleh teknisi IT.',
        ])->assertRedirect();

        $report->refresh();
        $this->assertEquals('Sedang Diproses', $report->status);

        // Update to Selesai with completion photo
        $this->actingAs($this->admin)->patch(route('reports.update-status', $report), [
            'status' => 'Selesai',
            'handling_notes' => 'PSU diganti dengan unit baru 500W, PC berjalan normal.',
            'completion_photo' => UploadedFile::fake()->image('after_repair.jpg'),
        ])->assertRedirect();

        $report->refresh();
        $this->assertEquals('Selesai', $report->status);
        $this->assertNotNull($report->completion_photo_path);
        Storage::disk('public')->assertExists($report->completion_photo_path);

        // Delete report
        $this->actingAs($this->admin)
            ->delete(route('reports.destroy', $report))
            ->assertRedirect(route('reports.index'));

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
    }

    public function test_discussion_and_notifications_work_between_user_and_admin(): void
    {
        $category = Category::first();
        $report = Report::create([
            'ticket_number' => 'TKT-20261125-0003',
            'user_id' => $this->reporter->id,
            'item_name' => 'Monitor LED 24 Inch',
            'category_id' => $category->id,
            'location' => 'Ruang Design',
            'problem_type' => 'Perangkat Rusak',
            'description' => 'Layar berkedip-kedip dan muncul garis vertikal.',
            'quantity' => 1,
            'priority' => 'Sedang',
            'status' => 'Pending',
        ]);

        $this->actingAs($this->reporter)->post(route('reports.comments.store', $report), [
            'comment' => 'Apakah ada unit monitor cadangan selama diperbaiki?',
        ])->assertRedirect();

        $this->assertDatabaseHas('report_comments', [
            'report_id' => $report->id,
            'user_id' => $this->reporter->id,
        ]);

        $this->actingAs($this->admin)->post(route('reports.comments.store', $report), [
            'comment' => 'Ada, teknisi akan mengantarkan unit cadangan pagi ini.',
        ])->assertRedirect();

        $notification = $this->reporter->notifications()->latest()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('new_comment', $notification->type);

        $this->actingAs($this->reporter)
            ->get(route('notifications.read', $notification))
            ->assertRedirect($notification->link);

        $notification->refresh();
        $this->assertTrue($notification->is_read);
    }

    public function test_admin_can_manage_categories_items_and_exports(): void
    {
        $this->actingAs($this->admin)->get('/exports')->assertStatus(200);
        $this->actingAs($this->admin)->get('/exports/pdf')->assertStatus(200)->assertHeader('content-type', 'application/pdf');
        $this->actingAs($this->admin)->get('/exports/excel')->assertStatus(200);

        $this->actingAs($this->admin)->post('/categories', [
            'name' => 'Keamanan Siber & CCTV',
            'description' => 'Perangkat NVR, kamera IP, dan token keamanan.',
        ])->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Keamanan Siber & CCTV']);

        $category = Category::first();
        $this->actingAs($this->admin)->post('/items', [
            'item_code' => 'IT-HW-099',
            'name' => 'Scanner Dokumen ADF',
            'category_id' => $category->id,
            'location' => 'Ruang Arsip',
            'stock_quantity' => 2,
            'condition' => 'Baik',
        ])->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', ['item_code' => 'IT-HW-099']);
    }

    public function test_create_admin_artisan_command(): void
    {
        $this->artisan('fixtrack:create-admin', [
            '--name' => 'Super Admin IT',
            '--email' => 'superadmin@example.com',
            '--password' => 'StrongPass#2026',
        ])->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'superadmin@example.com',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_user_can_self_register_with_forced_user_role(): void
    {
        $this->get('/register')->assertStatus(200)->assertSee('Daftar Akun Pelapor');

        $response = $this->post('/register', [
            'name' => 'Bambang',
            'email' => 'bambang@gmail.com',
            'phone' => '081234567899',
            'password' => 'bambang*123',
            'password_confirmation' => 'bambang*123',
            'role' => 'admin', // Attempt privilege escalation must be ignored
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'bambang@gmail.com',
            'role' => 'user',
            'status' => 'active',
        ]);
        $this->assertAuthenticated();
    }
}
