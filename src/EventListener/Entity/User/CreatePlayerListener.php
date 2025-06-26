<?php

declare(strict_types=1);

namespace App\EventListener\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Entity\Player;
use VideoGamesRecords\CoreBundle\Entity\PlayerBadge;

#[AsEntityListener(event: Events::postPersist, method: 'createPlayer', entity: User::class)]
readonly class CreatePlayerListener
{
    public const GROUP_PLAYER = 2;
    public const BADGE_REGISTER = 1;

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function createPlayer(User $user): void
    {
        // Role Player
        $group = $this->em->getReference('ProjetNormandie\UserBundle\Entity\Group', self::GROUP_PLAYER);
        $user->addGroup($group);

        // Player
        $player = new Player();
        $player->setId($user->getId());
        $player->setUserId($user->getId());
        $player->setPseudo($user->getUsername());

        $status = $this->em->getReference('VideoGamesRecords\CoreBundle\Entity\PlayerStatus', 1);
        $player->setStatus($status);

        $this->em->persist($player);

        // Register Badge
        $badge = $this->em->getReference('VideoGamesRecords\CoreBundle\Entity\Badge', self::BADGE_REGISTER);
        $playerBadge = new PlayerBadge();
        $playerBadge->setPlayer($player);
        $playerBadge->setBadge($badge);
        $this->em->persist($playerBadge);

        $this->em->flush();
    }
}
