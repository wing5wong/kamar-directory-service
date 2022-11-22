<?php

namespace Wing5wong\KamarDirectoryService\Auth;

class AuthenticationCheck
{
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->username = config('kamar-directory-service.username');
        $this->password = config('kamar-directory-service.password');
    }

    public function fails()
    {
        return request()->server('HTTP_AUTHORIZATION') !== ("Basic " . base64_encode($this->username . ':' . $this->password));
    }
}
