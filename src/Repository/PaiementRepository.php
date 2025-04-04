<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paiement>
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

    //    /**
    //     * @return Paiement[] Returns an array of Paiement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findTotalPaiementAnnee($user)
    {
        // Créer le QueryBuilder
        $queryBuilder = $this->createQueryBuilder('p');
        $rootAlias = $queryBuilder->getRootAliases()[0];

        // Définir des alias explicites
        $locataireAlias = 'l';
        $bienAlias = 'b';

        // Année courante
        $anneeActuelle = date('Y');

        // Dates de début et fin de l'année en cours
        $dateDebut = new \DateTime($anneeActuelle . '-01-01');
        $dateFin = new \DateTime($anneeActuelle . '-12-31');

        $queryBuilder
            ->select('SUM(' . $rootAlias . '.montant) as totalPaiement')
            ->leftJoin(sprintf('%s.locataire', $rootAlias), $locataireAlias)
            ->leftJoin(sprintf('%s.biens', $locataireAlias), $bienAlias)
            ->andWhere(sprintf('%s.users = :current_user', $bienAlias))
            ->andWhere($rootAlias . '.datePaiement BETWEEN :debut AND :fin')
            ->setParameter('current_user', $user)
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin);

        // On ne fait pas de GROUP BY puisqu'on ne renvoie que l'année en cours

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findTotalPaiementMoisAnnee($user)
    {
        // Créer le QueryBuilder
        $queryBuilder = $this->createQueryBuilder('p');
        $rootAlias = $queryBuilder->getRootAliases()[0];

        // Définir des alias explicites
        $locataireAlias = 'l';
        $bienAlias = 'b';

        // Mois et année courants
        $moisActuel = date('m');
        $anneeActuelle = date('Y');

        // Dates de début et fin du mois en cours
        $dateDebut = new \DateTime($anneeActuelle . '-' . $moisActuel . '-01');
        $dateFin = new \DateTime($anneeActuelle . '-' . $moisActuel . '-' . date('t'));

        $queryBuilder
            ->select('SUM(' . $rootAlias . '.montant) as totalPaiement')
            ->leftJoin(sprintf('%s.locataire', $rootAlias), $locataireAlias)
            ->leftJoin(sprintf('%s.biens', $locataireAlias), $bienAlias)
            ->andWhere(sprintf('%s.users = :current_user', $bienAlias))
            ->andWhere($rootAlias . '.datePaiement BETWEEN :debut AND :fin')
            ->setParameter('current_user', $user)
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }



    public function findStatusPaiement($user)
    {
        // Créer le QueryBuilder
        $queryBuilder = $this->createQueryBuilder('p');
        $rootAlias = $queryBuilder->getRootAliases()[0];

        // Définir des alias explicites
        $locataireAlias = 'l';
        $bienAlias = 'b';



        $queryBuilder
            ->leftJoin(sprintf('%s.locataire', $rootAlias), $locataireAlias)
            ->leftJoin(sprintf('%s.biens', $locataireAlias), $bienAlias)
            ->andWhere(sprintf('%s.users = :current_user', $bienAlias))
            ->setParameter('current_user', $user);
           

        return $queryBuilder->getQuery()->getResult();
    }

    
}
