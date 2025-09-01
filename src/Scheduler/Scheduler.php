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
use VideoGamesRecords\CoreBundle\Scheduler\Message\DailyRanking;
use VideoGamesRecords\CoreBundle\Scheduler\Message\UpdateYoutubeData;
use VideoGamesRecords\DwhBundle\Scheduler\Message\UpdateTable;

#[AsSchedule('default')]
class Scheduler implements ScheduleProviderInterface
{
    public function __construct(private readonly string $environment)
    {
    }

    public function getSchedule(): Schedule
    {
        $schedule = new Schedule();

        // APP
        //$schedule->add(RecurringMessage::cron('0 22 * * *', new UpdateUserRole()));

        // VGR-DWH (production only)
        if ($this->environment === 'production') {
            $schedule
                ->add(RecurringMessage::cron('5 0 * * *', new RedispatchMessage(new UpdateTable('game'), 'async')))
                ->add(RecurringMessage::cron('5 0 * * *', new RedispatchMessage(new UpdateTable('player'), 'async')))
                ->add(RecurringMessage::cron('5 0 * * *', new RedispatchMessage(new UpdateTable('team'), 'async')))
                ->add(RecurringMessage::cron('00 8 * * *', new DailyRanking()));
        }

        // VGR-CORE
        $schedule
            ->add(RecurringMessage::cron('00 8 * * 1', new UpdateYoutubeData()))
            ->add(RecurringMessage::cron('00 22 * * * ', new UpdatePlayerBadge()))
            ->add(RecurringMessage::cron('00 6,12,18 * * * ', new PurgeLostPosition()))
            ->add(RecurringMessage::cron('00 6 * * * ', new DesactivateScore()));

        // PN-TWITCH
        //$schedule->add(RecurringMessage::every('5 minutes', new RedispatchMessage(new UpdateStream(), 'async')));

        return $this->schedule ??= $schedule;
    }
}
