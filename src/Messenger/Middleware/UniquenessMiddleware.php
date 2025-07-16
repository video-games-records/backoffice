<?php

namespace App\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;
use Psr\Cache\CacheItemPoolInterface;

class UniquenessMiddleware implements MiddlewareInterface
{
    private CacheItemPoolInterface $cache;
    private int $ttl;

    public function __construct(CacheItemPoolInterface $cache, int $ttl = 3600)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        // Génère une clé unique basée sur le message
        $messageHash = $this->generateMessageHash($message);
        $cacheKey = 'messenger_message_' . $messageHash;

        // Vérifie si le message existe déjà
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            // Message déjà en queue, on ne l'ajoute pas
            return $envelope;
        }

        // Marque le message comme en cours de traitement
        $cacheItem->set(true);
        $cacheItem->expiresAfter($this->ttl);
        $this->cache->save($cacheItem);

        try {
            // Continue le traitement
            $result = $stack->next()->handle($envelope, $stack);

            // Supprime l'entrée du cache une fois le traitement terminé
            $this->cache->deleteItem($cacheKey);

            return $result;
        } catch (\Throwable $e) {
            // En cas d'erreur, supprime aussi l'entrée du cache
            $this->cache->deleteItem($cacheKey);
            throw $e;
        }
    }

    private function generateMessageHash($message): string
    {
        // Personnalisez cette méthode selon vos besoins
        if (method_exists($message, 'getUniqueIdentifier')) {
            return md5($message->getUniqueIdentifier());
        }

        return md5(serialize($message));
    }
}
