<?php

namespace App\Repository;

use App\Entity\Lieu;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Response;


class ApiRepository extends ServiceEntityRepository
{

    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Lieu::class);
        $this->entityManager = $entityManager;
    }

    // fonction qui retourne un json
    public function apiLieu(int $id_lieux)
    {

        $dql = 'SELECT l FROM App\Entity\Lieu l WHERE  l.id=1';
//        $query = $this->entityManager->createQuery($dql)->setParameter('id', $id_lieux);
        $query = $this->entityManager->createQuery($dql);


        return $query->getResult();
    }
}