<?php

namespace App\Repository;

use App\Data\FiltersData;
use App\Entity\Ads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Ads>
 *
 * @method Ads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ads[]    findAll()
 * @method Ads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ads::class);

    }
    public function filtersSearch(FiltersData $filtersData): QueryBuilder
    {

        $query = $this
            ->createQueryBuilder('a')
            ->select('b', 'c', 'a', 'g', 'f')
            ->join('a.car', 'c')
            ->join('c.brand', 'b')
            ->join('c.Gearbox', 'g')
            ->join('c.fuel', 'f');

        if(!empty($filtersData->quickSearch)) {
            $query = $query
                ->andWhere('a.title LIKE :quickSearch' )
                ->setParameter('quickSearch', "%{$filtersData->quickSearch}%");
        }
        if(!empty($filtersData->min)) {
            $query = $query
                ->andWhere('a.price >= :min')
                ->setParameter('min', "$filtersData->min");
        }
        if(!empty($filtersData->max)) {
            $query = $query
                ->andWhere('a.price <= :max')
                ->setParameter('max', "$filtersData->max");
        }
        if (!empty($filtersData->brands)) {
            $query = $query
                ->andWhere('c.brand IN (:brands)')
                ->setParameter('brands', $filtersData->brands);
        }
        if (!empty($filtersData->gearboxes)) {
            $query = $query
                ->andWhere('c.Gearbox IN (:gearboxes)')
                ->setParameter('gearboxes', $filtersData->gearboxes);
        }
        if(!empty($filtersData->minKm)) {
            $query = $query
                ->andWhere('a.kilometers >= :minKm')
                ->setParameter('minKm', "$filtersData->minKm");
        }
        if(!empty($filtersData->maxKm)) {
            $query = $query
                ->andWhere('a.kilometers <= :maxKm')
                ->setParameter('maxKm', "$filtersData->maxKm");
        }
        if (!empty($filtersData->fuels)) {
            $query = $query
                ->andWhere('c.fuel IN (:fuels)')
                ->setParameter('fuels', $filtersData->fuels);
        }

        return $query;

    }

}
