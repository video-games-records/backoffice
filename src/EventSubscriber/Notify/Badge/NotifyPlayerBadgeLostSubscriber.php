<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Badge;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Event\PlayerBadgeLost;

final class NotifyPlayerBadgeLostSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PlayerBadgeLost::class => 'sendMessage',
        ];
    }

    /**
     * @param PlayerBadgeLost $event
     * @throws ORMException
     */
    public function sendMessage(PlayerBadgeLost $event): void
    {
        $playerBadge = $event->getPlayerBadge();
        $game = $playerBadge->getBadge()->getGame();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PLAYER_BADGE');


        // Send MP
        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($playerBadge->getPlayer()->getUserId());
        $url = '/' . $recipient->getLanguage() . '/' . $game->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('playerBadge.lose.object', array(), null, $recipient->getLanguage()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('playerBadge.lose.message', array(), null, $recipient->getLanguage()),
                    $recipient->getUsername(),
                    $url,
                    $game->getName($recipient->getLanguage())
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
