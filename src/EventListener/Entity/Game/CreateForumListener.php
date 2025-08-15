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
    public const CATEGORY_ID = 8;

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function setForum(Game $game): void
    {
        $category = $this->em->getRepository('ProjetNormandie\ForumBundle\Entity\Category')
            ->findOneBy(['id' => self::CATEGORY_ID]);

        $forum = new Forum();
        $forum->setLibForum($game->getLibGameEn());
        $forum->setLibForumFr($game->getLibGameFr());
        $forum->setCategory($category);

        $game->setForum($forum);
    }
}
