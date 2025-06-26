<?php

declare(strict_types=1);

namespace App\EventListener\Entity\Game;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use ProjetNormandie\ForumBundle\Entity\Forum;
use VideoGamesRecords\CoreBundle\Entity\Game;

#[AsEntityListener(event: Events::prePersist, method: 'setForum', entity: Game::class)]
readonly class CreateForumListener
{
    public const FORUM_ID = 10953;

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function setForum(Game $game): void
    {
        $parent = $this->em->getRepository('ProjetNormandie\ForumBundle\Entity\Forum')
            ->findOneBy(['id' => self::FORUM_ID]);

        $forum = new Forum();
        $forum->setLibForum($game->getLibGameEn());
        $forum->setLibForumFr($game->getLibGameFr());
        $forum->setParent($parent);

        $game->setForum($forum);
    }
}
