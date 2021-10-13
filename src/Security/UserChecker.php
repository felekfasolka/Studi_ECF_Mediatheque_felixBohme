<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
        // User account is not activated
        if (!$user->isEnabled()) {
            throw new CustomUserMessageAuthenticationException('Your Account has not yet been activated by the operator of this service. Please be patient.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}