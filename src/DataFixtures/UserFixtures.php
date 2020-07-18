<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'user', function($i) {

            $user = new User();
            $user->setUsername('user'.$i);
            $user->setRoles(
                ($i === 0) ? (['ROLE_ADMIN']) : (['ROLE_USER'])
            );
            $user->setEmail('user'.$i.'@user.fr');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'password'
            ));

            return $user;
        });

        $manager->flush();
    }
}
