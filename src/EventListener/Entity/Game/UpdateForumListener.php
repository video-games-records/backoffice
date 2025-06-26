<?php

declare(strict_types=1);

namespace App\EventListener\Entity\Game;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use VideoGamesRecords\CoreBundle\Entity\Game;

#[AsEntityListener(event: Events::preUpdate, method: 'updateForum', entity: Game::class)]
readonly class UpdateForumListener
{
    public function updateForum(Game $game): void
    {
        $game->getForum()->setLibForum($game->getlibGameEn());
    }
}
