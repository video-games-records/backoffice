<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\PlayerChart;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use VideoGamesRecords\CoreBundle\Event\PlayerChartUpdated;

final class NotifyPlayerChartUpdatedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PlayerChartUpdated::class => 'sendMessage',
        ];
    }

    /**
     * @param PlayerChartUpdated $event
     * @throws ORMException
     */
    public function sendMessage(PlayerChartUpdated $event): void
    {
        $playerChart = $event->getPlayerChart();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('DEFAULT');

        // Send MP
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')->find($playerChart->getPlayer()->getUserId());
        $url = '/' . $recipient->getLocale() . '/' . $playerChart->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('playerChart.updated.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('playerChart.updated.message', array(), null, $recipient->getLocale()),
                    $recipient->getUsername(),
                    $url,
                    $playerChart->getChart()->getCompleteName($recipient->getLocale())
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
