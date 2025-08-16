<?php

namespace App\EventSubscriber\Notify\ProofRequest;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\UserBundle\Entity\User;
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
        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($proofRequest->getPlayerChart()->getPlayer()->getUserId());
        $url = '/' . $recipient->getLanguage() . '/' . $proofRequest->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('proof_request.notification.confirm.object', array(), null, $recipient->getLanguage()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof_request.notification.confirm.message', array(), null, $recipient->getLanguage()),
                    $recipient->getUsername(),
                    $url,
                    $proofRequest->getPlayerChart()->getChart()->getCompleteName($recipient->getLanguage())
                )
            )
            ->setRecipient($recipient)
            ->send();

        // Send MP (2)
        /** @var User $recipient */
        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($proofRequest->getPlayerRequesting()->getUserId());
        $this->messageBuilder
            ->setObject($this->translator->trans('proof_request.notification.accept.object', array(), null, $recipient->getLanguage()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof_request.notification.accept.message', array(), null, $recipient->getLanguage()),
                    $recipient->getUsername(),
                    $url,
                    $proofRequest->getPlayerChart()->getChart()->getCompleteName($recipient->getLanguage()),
                    $proofRequest->getPlayerChart()->getPlayer()->getPseudo(),
                    $proofRequest->getResponse()
                )
            )
            ->setRecipient($recipient)
            ->send();
    }
}
