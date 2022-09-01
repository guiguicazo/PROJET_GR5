<?php

namespace App\Repository;

use App\Entity\Date;
use App\Entity\Etat;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Date>
 *
 * @method Date|null find($id, $lockMode = null, $lockVersion = null)
 * @method Date|null findOneBy(array $criteria, array $orderBy = null)
 * @method Date[]    findAll()
 * @method Date[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateRepository extends ServiceEntityRepository
//repository pour l'affichage et la création des sorties
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Date::class);
    }

    public function add(Date $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Date $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function miseAjourEtat(Etat $fermer,Etat $archiver,Etat $enCours,Etat $passer): void
    {
        $listeSortie = $this->findAll();
        $dateJour1 = new DateTime();

        $dateJour2 = new DateTime();
        $dateJourArchive= $dateJour2->modify('-100 day');

        foreach ($listeSortie as $sortie) {
            $duree= $sortie->getDuree();
            $dateFinActivite = $sortie->getDateHeureDebut()->modify('+'.$duree.' minute');


            if ($dateFinActivite < $dateJour1) {
                $sortie->setEtatSortie($passer);
            }
//
            //&& $sortie->getEtat()==$passer->getId()
            if($sortie->getDateHeureDebut() < $dateJour1 && $dateFinActivite > $dateJour1) {
                $sortie->setEtatSortie($enCours);
            }
            if ($dateFinActivite < $dateJourArchive){
                $sortie->setEtatSortie($archiver);
            }
            $this->getEntityManager()->persist($sortie);
            $this->getEntityManager()->flush();
        }
    }





//    /**
//     * @return Date[] Returns an array of Date objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Date
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
