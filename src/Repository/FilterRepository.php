<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class FilterRepository extends ServiceEntityRepository{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // fonction pour filtrer les users
    public function UserFilter(string $text){
        $entityManager = $this->getEntityManager();
        $dql = "SELECT u FROM App\Entity\User u 
        WHERE u.username LIKE '% :$text %'";
        $query= $entityManager->createQuery($dql);
        return $query->getResult();
    }
}