<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 5, function (User $user) {
            $user->setEmail($this->faker->unique()->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles([User::ROLE_USER]);
        });

        $manager->flush();
    }
}
