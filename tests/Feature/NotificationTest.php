<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    private function makeUser(): User
    {
        $this->seq++;

        return User::create([
            'name'              => "Mahasiswa {$this->seq}",
            'email'             => "mhs{$this->seq}@student.telkomuniversity.ac.id",
            'password'          => 'password123',
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function user_can_view_notifications(): void
    {
        $user = $this->makeUser();

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Pesanan Baru',
            'message' => 'Kamu memiliki pesanan baru.',
        ]);

        $response = $this->actingAs($user)
            ->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('Pesanan Baru');
    }

    /** @test */
    public function user_cannot_view_other_users_notifications(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        Notification::create([
            'user_id' => $user2->id,
            'title' => 'Notifikasi Rahasia',
            'message' => 'Pesan milik user lain.',
        ]);

        $response = $this->actingAs($user1)
            ->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Notifikasi Rahasia');
    }

    /** @test */
    public function user_can_mark_notification_as_read(): void
    {
        $user = $this->makeUser();

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Pesanan Baru',
            'message' => 'Kamu memiliki pesanan baru.',
        ]);

        $response = $this->actingAs($user)
            ->patch(route('notifications.update', $notification->id));

        $response->assertRedirect(route('notifications.index'));

        $notification->refresh();
        $this->assertTrue($notification->is_read);
        $this->assertNotNull($notification->read_at);
    }

    /** @test */
    public function guest_cannot_access_notifications(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login'));
    }
}

