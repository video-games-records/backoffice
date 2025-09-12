<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Badge;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Event\TeamBadgeLost;

final class NotifyTeamBadgeLostSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TeamBadgeLost::class => 'notify',
        ];
    }

    /**
     * @param TeamBadgeLost $event
     * @throws ORMException
     */
    public function notify(TeamBadgeLost $event): void
    {
        $teamBadge = $event->getTeamBadge();
        $game = $teamBadge->getBadge()->getGame();
        $team = $teamBadge->getTeam();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_TEAM_BADGE');


        // Send MP to team leader
        $this->sendMessage($team->getLeader()->getUserId(), $game);

        // Send MP to players of team
        $query = $this->em->createQueryBuilder()
            ->select('p')
            ->from('VideoGamesRecords\CoreBundle\Entity\Player', 'p')
            ->innerJoin('p.playerGame', 'pg')
            ->where('p.team = :team')
            ->setParameter('team', $team)
            ->andWhere('pg.player != :player')
            ->setParameter('player', $team->getLeader())
            ->andWhere('pg.game = :game')
            ->setParameter('game', $game);

        $players = $query->getQuery()->getResult();
        foreach ($players as $player) {
            $this->sendMessage($player->getId(), $game);
        }
    }

    /**
     * @param $user_id
     * @param $game
     * @return void
     */
    private function sendMessage($user_id, $game): void
    {
        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')->find($user_id);
        $url = '/' . $recipient->getLanguage() . '/' . $game->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('teamBadge.lose.object', array(), null, $recipient->getLanguage()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('teamBadge.lose.message', array(), null, $recipient->getLanguage()),
                    $recipient->getUsername(),
                    $url,
                    $game->getName($recipient->getLanguage())
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
