<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\PlayerChart;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Event\Admin\AdminPlayerChartUpdated;

final class NotifyPlayerChartUpdatedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AdminPlayerChartUpdated::class => 'sendMessage',
        ];
    }

    /**
     * @param AdminPlayerChartUpdated $event
     * @throws ORMException
     */
    public function sendMessage(AdminPlayerChartUpdated $event): void
    {
        $playerChart = $event->getPlayerChart();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('DEFAULT');

        // Send MP
        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($playerChart->getPlayer()->getUserId());
        $url = '/' . $recipient->getLanguage() . '/' . $playerChart->getUrl();
        $this->messageBuilder
            ->setObject(
                $this->translator->trans('playerChart.updated.object', array(), null, $recipient->getLanguage())
            )
            ->setMessage(
                sprintf(
                    $this->translator->trans('playerChart.updated.message', array(), null, $recipient->getLanguage()),
                    $recipient->getUsername(),
                    $url,
                    $playerChart->getChart()->getCompleteName($recipient->getLanguage())
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
