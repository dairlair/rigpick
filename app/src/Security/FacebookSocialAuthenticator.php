<?php

namespace App\Security;

use App\Entity\Key;

class FacebookSocialAuthenticator extends AbstractSocialAuthenticator
{
    protected function socialNetworkSlug(): string
    {
        return Key::PROVIDER_FACEBOOK;
    }
}
