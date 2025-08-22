<?php

declare(strict_types=1);

namespace App\Scheduler;

use Symfony\Component\Messenger\Message\RedispatchMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use VideoGamesRecords\CoreBundle\Scheduler\Message\AddGameOfDay;
use VideoGamesRecords\CoreBundle\Scheduler\Message\DesactivateScore;
use VideoGamesRecords\CoreBundle\Scheduler\Message\PurgeLostPosition;
use VideoGamesRecords\CoreBundle\Scheduler\Message\UpdatePlayerBadge;
use VideoGamesRecords\CoreBundle\Scheduler\Message\DailyRankingMessage;
use VideoGamesRecords\CoreBundle\Scheduler\Message\UpdateYoutubeData;
use VideoGamesRecords\DwhBundle\Scheduler\Message\UpdateTable;

#[AsSchedule('default')]
class Scheduler implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
            // APP
            //->add(RecurringMessage::cron('0 22 * * *', new UpdateUserRole()))

            // VGR-DWH
            ->add(RecurringMessage::cron('5 0 * * *', new RedispatchMessage(new UpdateTable('game'), 'async')))
            ->add(RecurringMessage::cron('5 0 * * *', new RedispatchMessage(new UpdateTable('player'), 'async')))
            ->add(RecurringMessage::cron('5 0 * * *', new RedispatchMessage(new UpdateTable('team'), 'async')))

            // VGR-CORE
            ->add(RecurringMessage::cron('00 8 * * *', new DailyRankingMessage()))
            ->add(RecurringMessage::cron('00 8 * * 1', new UpdateYoutubeData()))
            ->add(RecurringMessage::cron('00 22 * * * ', new UpdatePlayerBadge()))
            ->add(RecurringMessage::cron('00 6,12,18 * * * ', new PurgeLostPosition()))
            ->add(RecurringMessage::cron('00 6 * * * ', new DesactivateScore()))
            ->add(RecurringMessage::cron('00 23 * * * ', new AddGameOfDay()))

            // PN-TWITCH
            //->add(RecurringMessage::every('5 minutes', new RedispatchMessage(new UpdateStream(), 'async')))
        ;
    }
}
