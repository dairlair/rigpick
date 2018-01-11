<?php

namespace App\Services;
use App\Entity\Url;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Marks urls as processed, and retrieve information about already processed urls.
 * 
 * @package App\Services
 */
class UrlMarker
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $url
     * @param int $threshold
     *
     * @return bool
     */
    public function isRecentlyUpdated(string $url, $threshold = 86400): bool
    {
        /** @var UrlRepository $urlRepository */
        $urlRepository = $this->entityManager->getRepository(Url::class);
        /** @var Url $entity */
        $entity = $urlRepository->findByUrl($url);

        if (!$entity) {

            return false;
        }

        if ($entity->getUpdatedAt()->getTimestamp() < (time() - $threshold)) {

            return false;
        }

        return true;
    }

    /**
     * @param string $url
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAsUpdated(string $url): void
    {
        /** @var UrlRepository $urlRepository */
        $urlRepository = $this->entityManager->getRepository(Url::class);
        /** @var Url $entity */
        $entity = $urlRepository->findByUrlOrCreate($url);
        $entity->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
