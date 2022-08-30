<?php

namespace App\Repository;

use App\Entity\Date;
use App\Entity\Campus;
use App\Entity\User;

use App\Form\RegistrationFormDateType;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;


class FilterRegistration extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Date::class);
    }


    /***************************************************************************/
    //filtre qui recherche dans la basse le nom de la sortie
    //public function NameDateFilter(string $text)
    //{
    //    $entityManager = $this->getEntityManager();
//
    //    $dql = "SELECT a FROM App\Entity\Date a
    //           WHERE a.nom LIKE :text";
    //    $query = $entityManager->createQuery($dql)->setParameter('text', '%' . $text . '%');
    //    return $query->getResult();
    //}
    /***************************************************************************/


    //filtre qui recherche les sortie en cours et ouverte
    public function DateFilterOpen()
    {
        $em = $this->getEntityManager();

        $listesortie = $em->getRepository("App\Entity\Date")->createQueryBuilder('d')
            ->where('a.etatSortie = 2 or a.etatSortie = 4');

        $query = $listesortie->getQuery()->getResult();
        return $query->getResult();
    }

    //filtre qui recherche si je suis l' organisateur de la sortie
    //public function DateFilterOrga(Integer $id)
    //{
    //    $entityManager = $this->getEntityManager();
//
    //    $dql = "SELECT a FROM App\Entity\Date a
    //           WHERE a.organisateur = id";
//
    //    $query = $entityManager->createQuery($dql)->setParameter('id', $id);
    //    return $query->getResult();
//
    //}


    //filtre qui recherche si la sortie est passer
    //public function DateFilterlast()
    //{
    //    $entityManager = $this->getEntityManager();
    //    $dql = "SELECT a FROM App\Entity\Date a
    //           WHERE datediff(a.dateLimiteInscritpion,current_date)>0 ";
    //    $query = $entityManager->createQuery($dql);
    //    return $query->getResult();
    //}

    //filtre qui recherche si je suis inscrit à la sortie
    public function sortieInscrit(User $user)
    {
        $em = $this->getEntityManager();
        $idUser = $user->getId();

        $sortieUser = $em->getRepository("App\Entity\Date")->createQueryBuilder('d')
            ->innerJoin('d.participants', 'p')
            ->where('p.id = :iduser')
            ->setParameter('iduser', $idUser)
            ->getQuery()->getResult();

        return $sortieUser;

    }

    //filtre qui recherche si je ne suis pas inscrit à la sortie
    public function sortieNonInscrit(User $user)
    {
        $em = $this->getEntityManager();
        $idUser = $user->getId();
        $sortieUser = $em->getRepository("App\Entity\Date")->createQueryBuilder('d')
            ->leftJoin('d.participants', 'u')
            ->addSelect('u')
            ->where('u.id != :iduser')
            ->setParameter('iduser', $idUser)
            ->getQuery()->getResult();
        return $sortieUser;

    }


    //filtre qui cherche suivant le campus
    public function DateCampus(Campus $campus)
    {

        $entityManager = $this->getEntityManager();
        $dql = "SELECT d FROM App\Entity\Date d JOIN App\Entity\Campus c  
           WHERE d.campus = :monCampus";
        $query = $entityManager->createQuery($dql)->setParameter('monCampus', $campus);

        return $query->getResult();
    }


    //filtre qui cherche suivant les dates
    public function startEndDate(DateTime $dateHeureDebut, DateTime $dateHeureFin)
    {
        if ($dateHeureDebut > $dateHeureFin) {
            return "vous ne pouvez pas mettre de date de debut antérieur a la fin";
        } else {
            $entityManager = $this->getEntityManager();
            $dql = "SELECT d FROM App\Entity\Date  d WHERE d.dateHeureDebut >= :dateHeureDebut and d.dateHeureDebut <= :dateHeureFin";
            $query = $entityManager->createQuery($dql)->setParameter('dateHeureDebut', $dateHeureDebut)->setParameter('dateHeureFin', $dateHeureFin);

            return $query->getResult();
        }

    }

    // filtre global qui lance la requete en fonction des différents filtres activés

    public function globalFilter(User $user,string $search,String $sortieNonInscrit,String $sortieInscrit,String $sortieOrganisateur,String $sortiePassee,Campus $campusFlitre,DateTime $dateStartRecup,DateTime $dateFinRecup)
    {
        $idUser = $user->getId();
        $idCampus =$campusFlitre->getId();

        $em = $this->getEntityManager();
        $builderFilter = $em->getRepository('App\Entity\Date')->createQueryBuilder('d')
            ->Where('d.campus = :monCampus')
            ->setParameter('monCampus',$idCampus)
            ->leftJoin('d.participants','p')
            ->addSelect('p');

            if ($sortieNonInscrit=='1')
            {
                $builderFilter
                    ->andwhere('p.id != :iduser')
                    ->setParameter('iduser',$idUser);
            }
            if ($sortieInscrit=="1") {
                $builderFilter
                    ->andWhere('p.id = :iduser')
                    ->setParameter('iduser', $idUser);
            }

            $builderFilter
            ->andWhere('d.dateHeureDebut >= :dateHeureDebut')
            ->setParameter('dateHeureDebut',$dateStartRecup)
            ->andWhere('d.dateHeureDebut <= :dateHeureFin')
            ->setParameter('dateHeureFin',$dateFinRecup);

            if ($sortieOrganisateur=='1'){
                $builderFilter
                    ->andWhere('d.organisateur = : iduser')
                    ->setParameter('iduser',$idUser);
             }
            if ($sortiePassee=='1'){
                $builderFilter
                    ->andWhere('datediff(d.dateLimiteInscritpion,current_date)>0');

            }


        $query = $builderFilter->getQuery()->getResult();


        return $query;

    }

}