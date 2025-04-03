<?php
// src/Doctrine/Extension/CurrentUserExtension.php

namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Bien;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        // S'appliquer uniquement à l'entité Bien
        if (Bien::class !== $resourceClass) {
            return;
        }

        // S'assurer qu'un utilisateur est connecté
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        // Filtrer par utilisateur
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.users = :current_user', $rootAlias))
            ->setParameter('current_user', $user);
    }
}