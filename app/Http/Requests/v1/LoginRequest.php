<?php

namespace App\Http\Requests\v1;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }

    public function authenticate(): string
    {
        Auth::shouldUse('api');
        $this->ensureIsNotRateLimited();

        $user = User::firstWhere('email', $this->input('email'));

        if (empty($user) || !Hash::check($this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed')
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        $user->tokens()->delete();
        $token = $user->createToken($this->throttleKey());


        return $token->plainTextToken;
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60)
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return str($this->input('email').'|'.$this->ip())->lower()->transliterate()->value();
    }
}
