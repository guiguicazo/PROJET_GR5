<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class FilterRepository extends ServiceEntityRepository{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function UserFilter(string $text){
        $entityManager = $this->getEntityManager();
        $dql = "SELECT u FROM App\Entity\User u
               WHERE u.username LIKE '% :$text %'";
        $query= $entityManager->createQuery($dql);
        return $query->getResult();
    }
}