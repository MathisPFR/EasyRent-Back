<?php
// src/Doctrine/Extension/CurrentUserExtension.php
namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Bien;
use App\Entity\Document;
use App\Entity\Locataire;
use App\Entity\Paiement;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        // S'assurer qu'un utilisateur est connecté
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        // Appliquer le filtre en fonction de l'entité
        if (Bien::class === $resourceClass) {
            // Pour l'entité Bien: filtrer directement par l'utilisateur
            $queryBuilder->andWhere(sprintf('%s.users = :current_user', $rootAlias))
                ->setParameter('current_user', $user);
        } elseif (Locataire::class === $resourceClass) {
            // Pour l'entité Locataire: joindre avec Bien et filtrer par l'utilisateur du bien
            $joinAlias = $queryNameGenerator->generateJoinAlias('biens');
            $queryBuilder->innerJoin(sprintf('%s.biens', $rootAlias), $joinAlias)
                ->andWhere(sprintf('%s.users = :current_user', $joinAlias))
                ->setParameter('current_user', $user);
        } elseif (Paiement::class === $resourceClass || Document::class === $resourceClass) {
            // Pour les entités Paiement et Document: joindre avec Locataire puis avec Bien et filtrer
            $locataireAlias = $queryNameGenerator->generateJoinAlias('locataire');
            $bienAlias = $queryNameGenerator->generateJoinAlias('biens');
            
            $queryBuilder->innerJoin(sprintf('%s.locataire', $rootAlias), $locataireAlias)
                ->innerJoin(sprintf('%s.biens', $locataireAlias), $bienAlias)
                ->andWhere(sprintf('%s.users = :current_user', $bienAlias))
                ->setParameter('current_user', $user);
        }
    }
}