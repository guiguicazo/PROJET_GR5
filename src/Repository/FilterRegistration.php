<?php

namespace App\Repository;

use App\Entity\Date;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class FilterRegistration extends ServiceEntityRepository{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Date::class);
    }



    //filtre qui recherche dans la basse le nom de la sortie
    public function NameDateFilter(string $text){
        $entityManager = $this->getEntityManager();

        $dql = "SELECT a FROM App\Entity\Date a
               WHERE a.nom LIKE :text";
        $query= $entityManager->createQuery($dql)-> setParameter('text','%'.$text.'%');
        return $query->getResult();
    }




    //filtre qui recherche les sortie en cours et ouverte
    public function DateFilterOpen(){
        $entityManager = $this->getEntityManager();

        $dql = "SELECT a FROM App\Entity\Date a
               WHERE a.etatSortie = 2 or a.etatSortie = 4";
        $query= $entityManager->createQuery($dql) ;
        return $query->getResult();
    }


}