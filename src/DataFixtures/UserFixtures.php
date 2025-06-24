<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\ORM\Mapping\ClassMetadata;
use ProjetNormandie\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @var array<mixed>
     */
    private array $users = [
        // USER 1
        [
            'id' => 1,
            'email' => 'admin@local.fr',
            'username' => 'admin',
            'plainPassword' => 'admin',
            'roles' => ['ROLE_SUPER_ADMIN'],
        ],
        // USER 2
        [
            'id' => 2,
            'email' => 'user@local.fr',
            'username' => 'user',
            'plainPassword' => 'user',
            'roles' => [],
        ],
    ];

    private function updateGeneratorType(ObjectManager $manager): void
    {
        $metadata = $manager->getClassMetaData("ProjetNormandie\\UserBundle\\Entity\\User");
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
    }

    public function load(ObjectManager $manager): void
    {
        $this->updateGeneratorType($manager);
        $this->loadUsers($manager);
        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @return void
     */
    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->users as $row) {
            $user = new User();
            $user->setId($row['id']);
            $user->setEmail($row['email']);
            $user->setUsername($row['username']);
            $user->setPlainPassword($row['plainPassword']);
            $user->setRoles($row['roles']);
            $manager->persist($user);
            $this->addReference('user' . $user->getId(), $user);
        }
    }
}
