<?php

namespace App\Repository;

use App\Entity\Serie;
use App\Entity\Pays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    /**
     * @return Serie Returns an array of Serie objects
     */
    public function findById($value): Serie
    {
        return $this->createQueryBuilder('serie')
            ->andWhere('serie.id = :val')
            ->setParameter('val', $value)
            ->orderBy('serie.titre', 'ASC')
            ->join("App\Entity\Pays", "pays")
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @return Serie[] Returns an array of Serie objects
     */
    public function findLasts($limit): array
    {
        return $this->createQueryBuilder('serie')
            ->orderBy('serie.premiereDiffusion', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

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
