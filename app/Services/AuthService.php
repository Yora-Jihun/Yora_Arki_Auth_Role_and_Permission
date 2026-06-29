<?php

namespace App\Services;

use App\Enums\InvitationStatus;
use App\Enums\UserRole;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InvitationCreated;
use App\Support\EmailFormatter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthService
{
    /**
     * @param  array{first_name: string, middle_name: string|null, last_name: string, suffix: string|null, role: UserRole|string, email: string, password: string}  $data
     */
    public function register(array $data, bool $verified = false): User
    {
        $role = $data['role'] instanceof UserRole ? $data['role'] : UserRole::from($data['role']);

        $user = new User([
            ...Arr::except($data, ['password', 'role']),
            'role' => $role->value,
        ]);
        $user->password = $data['password'];
        $user->setAttribute('fullname', $user->full_name);

        if ($verified) {
            $user->email_verified_at = Carbon::now();
        }

        $user->save();

        Invitation::where(function ($query) use ($user): void {
            $query->where('email', $user->email)
                ->orWhere('employee_id', $user->id);
        })
            ->where('status', InvitationStatus::Pending->value)
            ->each(fn (Invitation $invitation) => $user->notify(new InvitationCreated($invitation)));

        return $user;
    }

    /**
     * @param  array{email: string, password: string}  $credentials
     */
    public function login(array $credentials, bool $remember = false): User
    {
        $email = EmailFormatter::sanitize($credentials['email']);

        if ($email === null) {
            throw new AuthenticationException('This account is not found');
        }

        $user = User::where('email', $email)->first();

        if ($user === null) {
            throw new AuthenticationException('This account is not found');
        }

        if (! Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ], $remember)) {
            if (! $remember) {
                $this->forgetRememberCookie();
            }

            throw new AuthenticationException('Incorrect Password');
        }

        if (! $remember) {
            $this->forgetRememberCookie();
        }

        $authenticatedUser = Auth::user();

        if (! $authenticatedUser instanceof User) {
            throw new AuthenticationException('This account is not found');
        }

        return $authenticatedUser;
    }

    private function forgetRememberCookie(): void
    {
        Cookie::queue(Cookie::forget(Auth::getRecallerName()));
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
