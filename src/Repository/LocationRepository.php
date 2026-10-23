<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findOneByCountryAndCity(string $country, string $city): ?Location
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.country = :country')
            ->setParameter('country', $country)
            ->andWhere('l.city = :city')
            ->setParameter('city', $city)
            ->getQuery()
            ->getOneOrNullResult();
    }
}