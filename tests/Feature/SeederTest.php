<?php

namespace Tests\Feature;

use App\Models\Options;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_admin_login_and_email_come_from_config(): void
    {
        config([
            'sb.seed.admin_email' => 'boss@example.com',
            'sb.seed.admin_password' => 'secret-pass',
            'sb.options.admin_email' => 'inbox@example.com',
        ]);

        $this->seed();

        $admin = User::firstWhere('email', 'boss@example.com');

        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check('secret-pass', $admin->password));
        $this->assertSame('inbox@example.com', Options::firstWhere('name', 'admin_email')->value);
    }

    public function test_seeded_rows_have_both_timestamps(): void
    {
        $this->seed();

        $this->assertNotNull(User::first()->updated_at);
        $this->assertNotNull(Options::first()->updated_at);
        $this->assertNotNull(Service::first()->updated_at);
    }
}
