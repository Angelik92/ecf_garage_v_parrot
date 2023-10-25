<?php

namespace App\Repository;

use App\Entity\Fuels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fuels>
 *
 * @method Fuels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fuels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fuels[]    findAll()
 * @method Fuels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fuels::class);
    }

//    /**
//     * @return Fuels[] Returns an array of Fuels objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fuels
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
