<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_notifications(): void
    {
        $user = User::factory()->create();

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Pesanan Baru',
            'message' => 'Kamu memiliki pesanan baru.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)
            ->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('Pesanan Baru');
    }

    /** @test */
    public function user_cannot_view_other_users_notifications(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Notification::create([
            'user_id' => $user2->id,
            'title' => 'Notifikasi Rahasia',
            'message' => 'Pesan milik user lain.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user1)
            ->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Notifikasi Rahasia');
    }

    /** @test */
    public function user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Pesanan Baru',
            'message' => 'Kamu memiliki pesanan baru.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('notifications.update', $notification->id));

        $response->assertRedirect(route('notifications.index'));

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'user_id' => $user->id,
            'is_read' => true,
        ]);
    }

    /** @test */
    public function guest_cannot_access_notifications(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login'));
    }
}

