<?php

namespace App\Repository;

use App\Entity\SerieTv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SerieTv>
 *
 * @method SerieTv|null find($id, $lockMode = null, $lockVersion = null)
 * @method SerieTv|null findOneBy(array $criteria, array $orderBy = null)
 * @method SerieTv[]    findAll()
 * @method SerieTv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieTvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SerieTv::class);
    }

    //    /**
    //     * @return SerieTv[] Returns an array of SerieTv objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SerieTv
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return int[] Returns an array of Serie objects
     */
    public function findLastsIds($limit): array
    {
        $ids = array();
        $res = $this->createQueryBuilder('serie')
            ->orderBy('serie.premiereDiffusion', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        foreach ($res as $elem) {
            $ids[] = $elem->getId();
        }
        return $ids;
    }

    /**
     * @return int Returns an int for number of rows
     */
    public function countAll(): int
    {
        return $this->createQueryBuilder('serie')->select("count(serie.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }
}
