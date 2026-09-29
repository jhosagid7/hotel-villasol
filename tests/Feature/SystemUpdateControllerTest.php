<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SystemUpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::first();
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get(route('sistema.update.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authorized_admin_can_view_updater_dashboard()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('sistema.update.index'));
        $response->assertStatus(200);
        $response->assertSee('Actualizaciones del Sistema');
        $response->assertSee('sistema/actualizaciones/check');
    }

    public function test_check_endpoint_returns_json_response()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post(route('sistema.update.check'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'has_updates',
            'commits_behind',
            'current_commit',
            'branch',
            'pending_commits',
            'message'
        ]);
    }

    public function test_concurrent_update_is_blocked_by_lock()
    {
        $this->actingAs($this->adminUser);

        $lock = Cache::lock('system_update_in_progress', 600);
        $lock->get();

        $response = $this->post(route('sistema.update.apply'));
        $response->assertStatus(423); // 423 Locked
        $response->assertJson([
            'success' => false,
            'message' => 'Ya hay una actualización en progreso. Por favor espere.'
        ]);

        $lock->release();
    }
}
