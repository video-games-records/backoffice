<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify\Proof;

use App\EventSubscriber\Notify\AbstractNotifySubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\MessageBundle\Builder\MessageBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use VideoGamesRecords\CoreBundle\Event\ProofEvent;
use VideoGamesRecords\CoreBundle\VideoGamesRecordsCoreEvents;

final class NotifyProofRefusedSubscriber extends AbstractNotifySubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            VideoGamesRecordsCoreEvents::PROOF_REFUSED => 'sendMessage',
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
            ->setType('VGR_PROOF_REFUSED');

        $recipient = $this->em->getRepository('ProjetNormandie\UserBundle\Entity\User')
            ->find($proof->getPlayer()->getUserId());
        $url = '/' . $recipient->getLocale() . '/' . $proof->getPlayerChart()->getUrl();
        $this->messageBuilder
            ->setObject($this->translator->trans('proof.proof.refuse.object', array(), null, $recipient->getLocale()))
            ->setMessage(
                sprintf(
                    $this->translator->trans('proof.proof.refuse.message', array(), null, $recipient->getLocale()),
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
