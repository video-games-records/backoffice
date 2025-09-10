<?php

declare(strict_types=1);

namespace App\EventListener\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Entity\Player;
use VideoGamesRecords\CoreBundle\Entity\PlayerBadge;

#[AsEntityListener(event: Events::postUpdate, method: 'updatePlayer', entity: User::class)]
readonly class UpdatePlayerListener
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function updatePlayer(User $user): void
    {
        /** @var Player $player */
        $player = $this->em->getRepository('VideoGamesRecords\CoreBundle\Entity\Player')->find($user->getId());
        $player->setPseudo($user->getUsername());
        $player->setAvatar($user->getAvatar());
        $player->setLastLogin($user->getLastlogin());
        $player->setNbConnexion($user->getNbConnexion());

        $this->em->flush();
    }
}
