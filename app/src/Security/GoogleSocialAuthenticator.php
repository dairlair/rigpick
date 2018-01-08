<?php

namespace App\Security;

use App\Entity\Key;

class GoogleSocialAuthenticator extends AbstractSocialAuthenticator
{
    protected function socialNetworkSlug(): string
    {
        return Key::PROVIDER_GOOGLE;
    }
}
