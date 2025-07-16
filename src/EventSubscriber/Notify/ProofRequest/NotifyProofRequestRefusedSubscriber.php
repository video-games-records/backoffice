<?php

namespace App\EventSubscriber\Notify\ProofRequest;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use VideoGamesRecords\CoreBundle\Event\ProofRequestRefused;

final class NotifyProofRequestRefusedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProofRequestRefused::class => 'sendMessage',
        ];
    }

    /**
     * @param ProofRequestRefused $event
     * @throws ORMException
     */
    public function sendMessage(ProofRequestRefused $event): void
    {
        $proofRequest = $event->getProofRequest();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PROOF_REQUEST_REFUSED');

        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')->find($proofRequest->getPlayerRequesting()->getUserId());
        $url = '/' . $recipient->getLocale() . '/' . $proofRequest->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('proof.request.refuse.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof.request.refuse.message', array(), null, $recipient->getLocale()),
                    $recipient->getUsername(),
                    $url,
                    $proofRequest->getPlayerChart()->getChart()->getCompleteName($recipient->getLocale()),
                    $proofRequest->getPlayerChart()->getPlayer()->getPseudo(),
                    $proofRequest->getResponse()
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
