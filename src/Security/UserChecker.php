<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use App\Entity\User;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->IsVerified()) {
            //message if verification not done
            throw new CustomUserMessageAuthenticationException('Please verify your account by clicking the link sent to your email address when you registered. You need to validate your account to sign in.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        //other controls
    }
}
