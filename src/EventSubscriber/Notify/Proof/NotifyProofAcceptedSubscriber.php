<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Proof;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use VideoGamesRecords\CoreBundle\Event\ProofEvent;
use VideoGamesRecords\CoreBundle\VideoGamesRecordsCoreEvents;

final class NotifyProofAcceptedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            VideoGamesRecordsCoreEvents::PROOF_ACCEPTED => 'sendMessage',
        ];
    }

    /**
     * @param ProofEvent $event
     * @throws ORMException
     */
    public function sendMessage(ProofEvent $event): void
    {
        $proof = $event->getProof();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PROOF_ACCEPTED');

        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')->find($proof->getPlayerChart()->getPlayer()->getUserId());
        $url = '/' . $recipient->getLocale() . '/' . $proof->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('proof.proof.accept.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof.proof.accept.message', array(), null, $recipient->getLocale()),
                    $recipient->getUsername(),
                    $url,
                    $proof->getPlayerChart()->getChart()->getCompleteName($recipient->getLocale()),
                    $proof->getResponse()
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
