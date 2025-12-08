<?php

namespace App\Rules;

use App\Models\Otp;
use Illuminate\Contracts\Validation\Rule;

class ResetPassVerifyOtpRule implements Rule
{
    protected string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function passes($attribute, $value)
    {
        return Otp::where('email', $this->email)
            ->where('otp', $value)
            ->where('status', false)
            ->where('created_at', '>=', now()->subMinutes(60))
            ->exists();
    }

    public function message()
    {
        return 'The provided OTP is invalid or has expired.';
    }
}
