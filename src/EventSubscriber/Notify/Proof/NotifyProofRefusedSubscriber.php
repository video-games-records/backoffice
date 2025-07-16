<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Proof;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\MessageBundle\Builder\MessageBuilder;
use ProjetNormandie\UserBundle\Entity\User;
use VideoGamesRecords\CoreBundle\Event\ProofRefused;

final class NotifyProofRefusedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProofRefused::class => 'sendMessage',
        ];
    }

    /**
     * @param ProofRefused $event
     * @throws ORMException
     */
    public function sendMessage(ProofRefused $event): void
    {
        $proof = $event->getProof();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PROOF_REFUSED');

        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($proof->getPlayer()->getUserId());
        $url = '/' . $recipient->getLanguage() . '/' . $proof->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('proof.proof.refuse.object', array(), null, $recipient->getLanguage()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof.proof.refuse.message', array(), null, $recipient->getLanguage()),
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
