<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_without_remember_forgets_existing_remember_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        Cookie::queue(Cookie::make(Auth::getRecallerName(), 'stale', 525600));

        app(AuthService::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ], false);

        $queuedCookie = $this->queuedCookie(Auth::getRecallerName());

        $this->assertNotNull($queuedCookie);
        $this->assertTrue($queuedCookie->isCleared());
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_remember_queues_remember_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        app(AuthService::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ], true);

        $queuedCookie = $this->queuedCookie(Auth::getRecallerName());

        $this->assertNotNull($queuedCookie);
        $this->assertFalse($queuedCookie->isCleared());
        $this->assertAuthenticatedAs($user);
    }

    private function queuedCookie(string $name): ?\Symfony\Component\HttpFoundation\Cookie
    {
        return collect(Cookie::getQueuedCookies())
            ->first(fn (\Symfony\Component\HttpFoundation\Cookie $cookie): bool => $cookie->getName() === $name);
    }
}
