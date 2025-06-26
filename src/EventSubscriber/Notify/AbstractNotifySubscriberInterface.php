<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify;

use App\Contracts\VgrInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\MessageBundle\Builder\MessageBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractNotifySubscriberInterface implements EventSubscriberInterface, VgrInterface
{
    protected MessageBuilder $messageBuilder;
    protected TranslatorInterface $translator;
    protected EntityManagerInterface $em;

    /**
     * @param MessageBuilder $messageBuilder
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $em
     */
    public function __construct(MessageBuilder $messageBuilder, TranslatorInterface $translator, EntityManagerInterface $em)
    {
        $this->messageBuilder = $messageBuilder;
        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * @throws ORMException
     */
    protected function getDefaultSender()
    {
        return $this->em->getReference('ProjetNormandie\UserBundle\Entity\User', self::USER_SENDER_MESSAGE);
    }
}
