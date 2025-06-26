<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Badge;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use VideoGamesRecords\CoreBundle\Event\PlayerBadgeEvent;
use VideoGamesRecords\CoreBundle\VideoGamesRecordsCoreEvents;

final class NotifyPlayerBadgeLostSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            VideoGamesRecordsCoreEvents::PLAYER_BADGE_LOST => 'sendMessage',
        ];
    }

    /**
     * @param PlayerBadgeEvent $event
     * @throws ORMException
     */
    public function sendMessage(PlayerBadgeEvent $event): void
    {
        $playerBadge = $event->getPlayerBadge();
        $game = $playerBadge->getBadge()->getGame();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PLAYER_BADGE');


        // Send MP
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($playerBadge->getPlayer()->getUserId());
        $url = '/' . $recipient->getLocale() . '/' . $game->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('playerBadge.lose.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('playerBadge.lose.message', array(), null, $recipient->getLocale()),
                    $recipient->getUsername(),
                    $url,
                    $game->getName($recipient->getLocale())
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
