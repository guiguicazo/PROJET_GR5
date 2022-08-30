<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class FilterVilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    // fonction pour filtrer les villes

    public function VilleFilter(string $text)
    {
        $entityManager = $this->getEntityManager();

        $dql1 = "SELECT u FROM App\Entity\Ville u
               WHERE u.nom LIKE :text ";
        $query = $entityManager->createQuery($dql1)->setParameter('text', '%' . $text . '%');
        return $query->getResult();
    }
}