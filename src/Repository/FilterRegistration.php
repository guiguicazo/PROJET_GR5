<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Date;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class FilterRegistration extends ServiceEntityRepository{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Date::class);
    }


/***************************************************************************/
    //filtre qui recherche dans la basse le nom de la sortie
    public function NameDateFilter(string $text){
        $entityManager = $this->getEntityManager();

        $dql = "SELECT a FROM App\Entity\Date a
               WHERE a.nom LIKE :text";
        $query= $entityManager->createQuery($dql)-> setParameter('text','%'.$text.'%');
        return $query->getResult();
    }
/***************************************************************************/



    //filtre qui recherche les sortie en cours et ouverte
    public function DateFilterOpen(){
        $entityManager = $this->getEntityManager();

        $dql = "SELECT a FROM App\Entity\Date a
               WHERE a.etatSortie = 2 or a.etatSortie = 4";

        $query= $entityManager->createQuery($dql) ;
        return $query->getResult();
    }

    //filtre qui recherche si je suis l' organisateur de la sortie
    public function DateFilterOrga(Integer $id){
        $entityManager = $this->getEntityManager();

        $dql = "SELECT a FROM App\Entity\Date a
               WHERE a.organisateur = id" ;
        $query= $entityManager->createQuery($dql)-> setParameter('id',$id);
        return $query->getResult();
    }



    //filtre qui recherche si la sortie est passer
    public function DateFilterlast(){
        $entityManager = $this->getEntityManager();
        $dql = "SELECT a FROM App\Entity\Date a
               WHERE datediff(a.dateLimiteInscritpion,current_date)>0 " ;
        $query= $entityManager->createQuery($dql);
        return $query->getResult();
    }


    //filtre qui cherche suivant le campus
    public function DateCampus(Campus $campus){

        $entityManager = $this->getEntityManager();
        $dql ="SELECT d FROM App\Entity\Date d JOIN App\Entity\Campus c  
           WHERE d.campus = :monCampus" ;
        $query= $entityManager->createQuery($dql)-> setParameter('monCampus',$campus);

        return $query->getResult();
    }


    //filtre qui cherche suivant les dates
    public function startEndDate(DateTimeType $dateHeureDebut, DateTimeType $dateHeureFin){
        if ($dateHeureDebut >  $dateHeureFin){
            return "vous ne pouvez pas mettre de date de debut antérieur a la fin" ;
        }else{
            $entityManager = $this->getEntityManager();
            $dql ="SELECT d FROM App\Entity\Date  d WHERE d.dateHeureDebut >= :dateHeureDebut and d.dateHeureDebut <= :dateHeureFin" ;
            $query= $entityManager->createQuery($dql)-> setParameter('dateHeureDebut',$dateHeureDebut) ->setParameter('dateHeureFin', $dateHeureFin);

            return $query->getResult();
        }

    }



}