<?php

namespace App\Repository;

use App\Entity\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class FilterCampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }


    // fonction pour filtrer les campus

    public function CampusFilter(string $text)
    {


        $entityManager = $this->getEntityManager();

        $dql1 = "SELECT u FROM App\Entity\Campus u
               WHERE u.nom LIKE :text ";
        $query = $entityManager->createQuery($dql1)->setParameter('text', '%' . $text . '%');
        return $query->getResult();
    }
}