<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createConfirmedUsers();
        $this->createUnConfirmedUsers();
        $manager->flush();
    }

    private function createConfirmedUsers(int $count = 1): void
    {
        $this->createMany($count, 'confirmed_users', function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles([User::ROLE_USER]);
            return $user;
        });
    }

    private function createUnConfirmedUsers(int $count = 1): void
    {
        $this->createMany($count, 'unconfirmed_users', function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setConfirmToken($this->tokenGenerator->getRandomSecureToken());
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles([User::ROLE_USER]);
            return $user;
        });
    }
}
