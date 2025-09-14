<?php

// src/EventSubscriber/Discord/TeamMasterBadgeDiscordSubscriber.php

declare(strict_types=1);

namespace App\EventSubscriber\Discord;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Psr\Log\LoggerInterface;
use VideoGamesRecords\CoreBundle\Entity\Badge;
use VideoGamesRecords\CoreBundle\Entity\Game;
use VideoGamesRecords\CoreBundle\Entity\Team;
use VideoGamesRecords\CoreBundle\Event\TeamBadgeObtained;

final readonly class TeamMasterBadgeDiscordSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ChatterInterface $chatter,
        private ?LoggerInterface $logger = null
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TeamBadgeObtained::class => 'onTeamBadgeObtained',
        ];
    }

    /**
     * @param TeamBadgeObtained $event
     */
    public function onTeamBadgeObtained(TeamBadgeObtained $event): void
    {
        try {
            $teamBadge = $event->getTeamBadge();
            $badge = $teamBadge->getBadge();
            $team = $teamBadge->getTeam();
            $game = $badge->getGame();

            if (!$badge->isTypeMaster()) {
                return;
            }

            $this->sendDiscordNotification($team, $game, $badge);
        } catch (\Exception $e) {
            $this->logger?->error('Error sending Discord notification for team Master badge', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function sendDiscordNotification(Team $team, Game $game, Badge $badge): void
    {
        $message = sprintf(
            "🏆 **NEW TEAM MASTER BADGE!** 🏆\n\n" .
            "**Team:** %s\n" .
            "**Game:** %s\n" .
            "Congratulations! 🎉",
            $team->getLibTeam(),
            $game->getLibGameEn()
        );

        $chatMessage = new ChatMessage($message);
        $this->chatter->send($chatMessage);

        $this->logger?->info('Discord notification sent for team Master badge', [
            'team_id' => $team->getId(),
            'team_name' => $team->getLibTeam(),
            'game_id' => $game->getId(),
            'badge_id' => $badge->getId()
        ]);
    }
}
