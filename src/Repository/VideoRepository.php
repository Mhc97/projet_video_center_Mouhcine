<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Video>
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    /**
     * Recherche les vidéos en fonction d'un terme de recherche et de la visibilité premium
     */
    public function findBySearchAndVisibility(?string $search, bool $showPremium): Query
    {
        $qb = $this->createQueryBuilder('v');

        // Recherche par titre ou description
        if ($search && !empty($search)) {
            $qb->andWhere('v.title LIKE :search OR v.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        // Filtrer les vidéos premium (si l'utilisateur n'est pas vérifié)
        if (!$showPremium) {
            $qb->andWhere('v.premiumVideo = false');
        }

        $qb->orderBy('v.createdAt', 'DESC');

        return $qb->getQuery();
    }

    /**
     * Recherche simple pour la pagination
     */
    public function search(?string $searchTerm): array
    {
        $qb = $this->createQueryBuilder('v');
        
        if ($searchTerm && !empty($searchTerm)) {
            $qb->where('v.title LIKE :search')
               ->orWhere('v.description LIKE :search')
               ->setParameter('search', '%' . $searchTerm . '%');
        }
        
        return $qb->orderBy('v.createdAt', 'DESC')
                  ->getQuery()
                  ->getResult();
    }
}