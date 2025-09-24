<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Proof;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Event\ProofAccepted;

final class NotifyProofAcceptedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProofAccepted::class => 'sendMessage',
        ];
    }

    /**
     * @param ProofAccepted $event
     * @throws ORMException
     */
    public function sendMessage(ProofAccepted $event): void
    {
        $proof = $event->getProof();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PROOF_ACCEPTED');

        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($proof->getPlayerChart()->getPlayer()->getUserId());
        $url = '/' . $recipient->getLanguage() . '/' . $proof->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject(
                $this->translator->trans(
                    'proof_accepted.object',
                    [],
                    'VgrCoreNotification',
                    $recipient->getLanguage()
                )
            )
            ->setMessage(
                sprintf(
                    $this->translator->trans(
                        'proof_accepted.message',
                        [],
                        'VgrCoreNotification',
                        $recipient->getLanguage()
                    ),
                    $recipient->getUsername(),
                    $url,
                    $proof->getPlayerChart()->getChart()->getCompleteName($recipient->getLanguage()),
                    $proof->getResponse()
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
