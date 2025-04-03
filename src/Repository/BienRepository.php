<?php

namespace App\Repository;

use App\Entity\Bien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bien>
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

    /**
     * @return Bien[] Returns an array of Bien objects
     */
    public function findByAdresseAndUser($value, $user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.users = :valuser')
            ->setParameter('valuser', $user)
            ->andWhere('b.adresse LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult()
        ;
    }
}
