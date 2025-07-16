<?php

namespace App\EventSubscriber\Notify\ProofRequest;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use VideoGamesRecords\CoreBundle\Event\ProofRequestAccepted;

final class NotifyProofRequestAcceptedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProofRequestAccepted::class => 'sendMessage',
        ];
    }

    /**
     * @param ProofRequestAccepted $event
     * @throws ORMException
     */
    public function sendMessage(ProofRequestAccepted $event): void
    {
        $proofRequest = $event->getProofRequest();
        $this->messageBuilder
            ->setSender($this->getDefaultSender())
            ->setType('VGR_PROOF_REQUEST_ACCEPTED');

        // Send MP (1)
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')->find($proofRequest->getPlayerChart()->getPlayer()->getUserId());
        $url = '/' . $recipient->getLocale() . '/' . $proofRequest->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('proof.request.confirm.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof.request.confirm.message', array(), null, $recipient->getLocale()),
                    $recipient->getUsername(),
                    $url,
                    $proofRequest->getPlayerChart()->getChart()->getCompleteName($recipient->getLocale())
                )
            )
            ->setRecipient($recipient)
            ->send();

        // Send MP (2)
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')->find($proofRequest->getPlayerRequesting()->getUserId());
        $this->messageBuilder
            ->setObject($this->translator->trans('proof.request.accept.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof.request.accept.message', array(), null, $recipient->getLocale()),
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
