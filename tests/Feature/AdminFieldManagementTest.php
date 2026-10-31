<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFieldManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest is redirected to login when accessing admin dashboard.
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test customer receives 403 forbidden when accessing admin dashboard.
     */
    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Manajemen KickFlow');
    }

    /**
     * Test guest is redirected to login when accessing admin fields.
     */
    public function test_guest_cannot_access_admin_fields(): void
    {
        $response = $this->get('/admin/fields');

        $response->assertRedirect('/login');
    }

    /**
     * Test regular customer receives 403 forbidden when accessing admin fields.
     */
    public function test_customer_cannot_access_admin_fields(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/admin/fields');

        $response->assertStatus(403);
    }

    /**
     * Test admin can view fields index page.
     */
    public function test_admin_can_view_fields_index_page(): void
    {
        $admin = User::factory()->admin()->create();
        $field = Field::factory()->create(['name' => 'Lapangan Senayan VVIP']);

        $response = $this->actingAs($admin)->get('/admin/fields');

        $response->assertStatus(200);
        $response->assertSee('Lapangan Senayan VVIP');
        $response->assertSee('Tambah Lapangan');
    }

    /**
     * Test admin can view create field page.
     */
    public function test_admin_can_view_create_field_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/fields/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Lapangan Baru');
    }

    /**
     * Test admin can store a new field.
     */
    public function test_admin_can_store_a_new_field(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $image = UploadedFile::fake()->image('lapangan-baru.jpg', 600, 400);

        $response = $this->actingAs($admin)->post('/admin/fields', [
            'name' => 'Arena Futsal Megah',
            'type' => 'indoor',
            'price_per_hour' => 175000,
            'description' => 'Rumput sintetis kualitas internasional dengan lampu LED 1000 watt.',
            'image' => $image,
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/fields');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('fields', [
            'name' => 'Arena Futsal Megah',
            'type' => 'indoor',
            'price_per_hour' => 175000,
            'is_active' => true,
        ]);

        $field = Field::where('name', 'Arena Futsal Megah')->first();
        $this->assertNotNull($field->image_path);
        Storage::disk('public')->assertExists($field->image_path);
    }

    /**
     * Test store field validation requires mandatory fields.
     */
    public function test_store_field_requires_mandatory_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/fields', [
            'name' => '',
            'type' => '',
            'price_per_hour' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'type', 'price_per_hour']);
    }

    /**
     * Test admin can view edit field page.
     */
    public function test_admin_can_view_edit_field_page(): void
    {
        $admin = User::factory()->admin()->create();
        $field = Field::factory()->create();

        $response = $this->actingAs($admin)->get("/admin/fields/{$field->id}/edit");

        $response->assertStatus(200);
        $response->assertSee($field->name);
        $response->assertSee('Edit Lapangan');
    }

    /**
     * Test admin can update a field.
     */
    public function test_admin_can_update_a_field(): void
    {
        $admin = User::factory()->admin()->create();
        $field = Field::factory()->create([
            'name' => 'Nama Lama',
            'type' => 'indoor',
            'price_per_hour' => 100000,
        ]);

        $response = $this->actingAs($admin)->put("/admin/fields/{$field->id}", [
            'name' => 'Nama Baru Diperbarui',
            'type' => 'outdoor',
            'price_per_hour' => 120000,
            'description' => 'Deskripsi baru yang sudah diubah.',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/fields');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('fields', [
            'id' => $field->id,
            'name' => 'Nama Baru Diperbarui',
            'type' => 'outdoor',
            'price_per_hour' => 120000,
        ]);
    }

    /**
     * Test admin can toggle field status between active and inactive.
     */
    public function test_admin_can_toggle_field_status(): void
    {
        $admin = User::factory()->admin()->create();
        $field = Field::factory()->create(['is_active' => true]);

        // Toggle to inactive
        $response = $this->actingAs($admin)->patch("/admin/fields/{$field->id}/toggle-status");
        $response->assertRedirect('/admin/fields');
        $this->assertFalse($field->fresh()->is_active);

        // Toggle back to active
        $response = $this->actingAs($admin)->patch("/admin/fields/{$field->id}/toggle-status");
        $response->assertRedirect('/admin/fields');
        $this->assertTrue($field->fresh()->is_active);
    }

    /**
     * Test admin can delete a field.
     */
    public function test_admin_can_delete_a_field(): void
    {
        $admin = User::factory()->admin()->create();
        $field = Field::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/fields/{$field->id}");

        $response->assertRedirect('/admin/fields');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('fields', ['id' => $field->id]);
    }
}
