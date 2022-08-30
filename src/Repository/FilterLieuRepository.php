<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class FilterLieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }


    // fonction pour filtrer les lieux

    public function LieuFilter(string $text)
    {


        $entityManager = $this->getEntityManager();

        $dql1 = "SELECT u FROM App\Entity\Lieu u
               WHERE u.nom LIKE :text ";
        $query = $entityManager->createQuery($dql1)->setParameter('text', '%' . $text . '%');
        return $query->getResult();
    }
}