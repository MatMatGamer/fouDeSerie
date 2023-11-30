<?php

namespace App\Repository;

use App\Entity\SerieWeb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SerieWeb>
 *
 * @method SerieWeb|null find($id, $lockMode = null, $lockVersion = null)
 * @method SerieWeb|null findOneBy(array $criteria, array $orderBy = null)
 * @method SerieWeb[]    findAll()
 * @method SerieWeb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieWebRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SerieWeb::class);
    }

    //    /**
    //     * @return SerieWeb[] Returns an array of SerieWeb objects
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

    //    public function findOneBySomeField($value): ?SerieWeb
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
     * @return SerieWeb[] Returns an array of Serie objects
     */
    public function findBetweenDates($date1, $date2): array
    {
        $res = $this->createQueryBuilder('serie')
            ->where("serie.premiereDiffusion >= :date1")
            ->andWhere("serie.premiereDiffusion <= :date2")
            ->setParameter(":date1", $date1 . "-01")
            ->setParameter(":date2", $date2 . "-31")
            ->orderBy('serie.titre', 'ASC')
            ->getQuery()
            ->getResult();
        return $res;
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
