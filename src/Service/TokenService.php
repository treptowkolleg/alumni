<?php

namespace App\Service;

class TokenService
{

    private string $appSecret;

    public function __construct(string $appSecret)
    {
        $this->appSecret = $appSecret;
    }

    public function generateUnsubscribeToken(string $email): string
    {
        return hash_hmac('sha256', $email, $this->appSecret);
    }

}