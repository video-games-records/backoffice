<?php

declare(strict_types=1);

namespace App\EventSubscriber\Notify;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use ProjetNormandie\MessageBundle\Builder\MessageBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractNotifySubscriberInterface implements EventSubscriberInterface
{
    /**
     * @param MessageBuilder $messageBuilder
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        protected readonly MessageBuilder $messageBuilder,
        protected readonly TranslatorInterface $translator,
        protected readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @throws ORMException
     */
    protected function getDefaultSender()
    {
        return $this->em->getReference('ProjetNormandie\UserBundle\Entity\User', 0);
    }
}
