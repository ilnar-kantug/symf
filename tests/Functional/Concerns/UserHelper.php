<?php

namespace App\Tests\Functional\Concerns;

use App\Entity\User;

trait UserHelper
{
    public function createAdmin()
    {
        $email = 'admin@admin.admin';
        $pass = 'password';

        $admin = new User();
        $admin->setEmail($email);
        $admin->setPassword($this->encodePassword($admin, $pass));
        $admin->setStatus(User::STATUS_ACTIVE);
        $admin->setFullName('blank');
        $admin->setRoles([User::ROLE_ADMIN]);

        $this->em->persist($admin);

        $this->em->flush();

        return [
            'PHP_AUTH_USER' => $email,
            'PHP_AUTH_PW' => $pass,
        ];
    }

    public function encodePassword(User $user, string $pass)
    {
        $encoder = static::$kernel->getContainer()->get('pass.encoder');
        return $encoder->encodePassword($user, $pass);
    }
}
