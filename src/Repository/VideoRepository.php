<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
<<<<<<< HEAD
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
=======
// use Doctrine\Persistence\ManagerRegistry;
>>>>>>> d50f532ab68544ec6c44f4a594fd113e818452b1

/**
 * @extends ServiceEntityRepository<Video>
 */
class VideoRepository extends ServiceEntityRepository
{
    // public function __construct(ManagerRegistry $registry)
    // {
    //     parent::__construct($registry, Video::class);
    // }

    public function findBySearchAndVisibility(?string $search, bool $showPremium): Query
    {
        $qb = $this->createQueryBuilder('v');

        // Recherche par titre ou description
        if ($search){
            $qb->andWhere('v.title LIKE :search OR v.description LIKE :search')
             ->setParameter('search', '%' . $search . '%');
        }

        // Filtrer les vidéos premium
        if (!$showPremium){
            $qb->andWhere('v.premiumVideo = false');
        }

        $qb->orderBy('v.createdAt', 'DESC');

        return $qb->getQuery();
    }

    //    /**
    //     * @return Video[] Returns an array of Video objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function search($searchTerm): array
    {
         return $this->createQueryBuilder('v')
               ->where('v.title LIKE :search')
               ->orWhere('v.description LIKE :search')
               ->setParameter('search', '%' . $searchTerm . '%')
               ->getQuery()
               ->getResult()
           ;
    }

    //    public function findOneBySomeField($value): ?Video
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


}
