<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_login_always_redirects_to_landing_page(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->withSession(['url.intended' => route('bookings.index')])
            ->post(route('login'), [
                'email' => $customer->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('home'));
    }

    public function test_admin_login_always_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->withSession(['url.intended' => route('home')])
            ->post(route('login'), [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('dashboard'));
    }
}
