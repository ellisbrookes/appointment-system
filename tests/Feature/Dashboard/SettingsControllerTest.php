<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_view_settings_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/settings');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.settings.index');
        $response->assertViewHas('settings');
    }

    #[Test]
    public function authenticated_user_can_update_settings()
    {
        $user = User::factory()->create();

        $settingsData = [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 60,
                'time_format' => '24',
                'timezone' => 'UTC',
            ]
        ];

        $response = $this->actingAs($user)->put('/dashboard/settings', $settingsData);

        $response->assertRedirect('/dashboard/settings');
        $response->assertSessionHas('alert.type', 'success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'settings' => json_encode($settingsData['settings']),
        ]);
    }

    #[Test]
    public function settings_update_requires_navigation_style()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 60,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.navigation_style']);
    }

    #[Test]
    public function settings_update_validates_navigation_style_options()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'invalid_option',
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 60,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.navigation_style']);
    }

    #[Test]
    public function settings_update_requires_timeslot_start()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 60,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_start']);
    }

    #[Test]
    public function settings_update_requires_timeslot_end()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '09:00',
                'timeslot_interval' => 60,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_end']);
    }

    #[Test]
    public function settings_update_validates_timeslot_end_after_start()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '17:00',
                'timeslot_end' => '09:00',
                'timeslot_interval' => 60,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_end']);
    }

    #[Test]
    public function settings_update_validates_time_format()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => 'invalid-time',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 60,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_start']);
    }

    #[Test]
    public function settings_update_requires_timeslot_interval()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_interval']);
    }

    #[Test]
    public function settings_update_validates_timeslot_interval_minimum()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 1,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_interval']);
    }

    #[Test]
    public function settings_update_validates_timeslot_interval_maximum()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 150,
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_interval']);
    }

    #[Test]
    public function settings_update_validates_interval_is_integer()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/dashboard/settings', [
            'settings' => [
                'navigation_style' => 'sidebar',
                'timeslot_start' => '09:00',
                'timeslot_end' => '17:00',
                'timeslot_interval' => 'not-an-integer',
            ]
        ]);

        $response->assertSessionHasErrors(['settings.timeslot_interval']);
    }

    #[Test]
    public function settings_page_displays_current_user_settings()
    {
        $user = User::factory()->create([
            'settings' => [
                'navigation_style' => 'top_nav',
                'timeslot_start' => '08:00',
                'timeslot_end' => '18:00',
                'timeslot_interval' => 30,
                'time_format' => '24',
                'timezone' => 'UTC',
            ]
        ]);

        $response = $this->actingAs($user)->get('/dashboard/settings');

        $response->assertStatus(200);
        $response->assertViewHas('settings', $user->settings);
    }

    #[Test]
    public function unauthenticated_users_cannot_access_settings()
    {
        $response = $this->get('/dashboard/settings');

        $response->assertRedirect('/auth/login');
    }

    #[Test]
    public function unauthenticated_users_cannot_update_settings()
    {
        $response = $this->put('/dashboard/settings', []);

        $response->assertRedirect('/auth/login');
    }
}
