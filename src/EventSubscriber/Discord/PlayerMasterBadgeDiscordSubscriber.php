<?php

// src/EventSubscriber/Discord/PlayerMasterBadgeDiscordSubscriber.php

declare(strict_types=1);

namespace App\EventSubscriber\Discord;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Psr\Log\LoggerInterface;
use VideoGamesRecords\CoreBundle\Entity\Badge;
use VideoGamesRecords\CoreBundle\Entity\Game;
use VideoGamesRecords\CoreBundle\Entity\Player;
use VideoGamesRecords\CoreBundle\Event\PlayerBadgeObtained;

final readonly class PlayerMasterBadgeDiscordSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ChatterInterface $chatter,
        private ?LoggerInterface $logger = null
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PlayerBadgeObtained::class => 'onPlayerBadgeObtained',
        ];
    }

    /**
     * @param PlayerBadgeObtained $event
     */
    public function onPlayerBadgeObtained(PlayerBadgeObtained $event): void
    {
        try {
            $playerBadge = $event->getPlayerBadge();
            $badge = $playerBadge->getBadge();
            $player = $playerBadge->getPlayer();
            $game = $badge->getGame();

            if (!$badge->isTypeMaster()) {
                return;
            }

            $this->sendDiscordNotification($player, $game, $badge);
        } catch (\Exception $e) {
            $this->logger?->error('Error sending Discord notification for player Master badge', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function sendDiscordNotification(Player $player, Game $game, Badge $badge): void
    {
        $message = sprintf(
            "🏆 **NEW MASTER BADGE ACHIEVED!** 🏆\n\n" .
            "**Player:** %s\n" .
            "**Game:** %s\n" .
            "Congratulations! 🎉",
            $player->getPseudo(),
            $game->getLibGameEn()
        );

        $chatMessage = new ChatMessage($message);
        $this->chatter->send($chatMessage);

        $this->logger?->info('Discord notification sent for player Master badge', [
            'player_id' => $player->getId(),
            'player_pseudo' => $player->getPseudo(),
            'game_id' => $game->getId(),
            'badge_id' => $badge->getId()
        ]);
    }
}
