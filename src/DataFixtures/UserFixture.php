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
        $this->createConfirmedUsers(20);
        $this->createUnConfirmedUsers();
        $this->createAdminUser();
        $manager->flush();
    }

    private function createConfirmedUsers(int $count = 1): void
    {
        $this->createMany($count, 'confirmed_users', function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles([User::ROLE_USER]);
            $user->setFullName($this->faker->name);
            $user->setStatus(User::STATUS_ACTIVE);
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
            $user->setFullName($this->faker->name);
            $user->setStatus(User::STATUS_NOT_VERIFIED);
            return $user;
        });
    }

    private function createAdminUser(): void
    {
        $this->create(function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles([User::ROLE_ADMIN]);
            $user->setFullName($this->faker->name);
            $user->setStatus(User::STATUS_ACTIVE);
            return $user;
        });
    }
}
