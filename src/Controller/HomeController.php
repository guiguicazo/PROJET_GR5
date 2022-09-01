<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Form\RegistrationFormDateType;//importation du formulaire Registartion
use App\Repository\CampusRepository;
use App\Repository\DateRepository;
use App\Repository\EtatRepository;
use App\Repository\FilterRegistration;
use App\Repository\FilterRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\Repository\LieuRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Date; //import l'Entité Date
use App\Entity\User; //import l'entité User

//import l'Entité Date


//import l'entité User


use App\Form\CreerUneSortieType;


//importation du formulaire CreeUneSortie


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        return $this->redirectToRoute('app_recapAll');
        //return $this->render('home/index.html.twig', [
        //    'controller_name' => 'HomeController',
        //]);
    }




    /**
     * affichage du formulaire vide
     */
    #[Route('/CreerSortie/{idUser}', name: 'app_sortie')]
    public function CreerSortie($idUser,
                                Request $request,
                                EntityManagerInterface $entityManager,
                                UserRepository $userRepository,
                                VilleRepository $villeRepository,
                                LieuRepository $lieuRepository,
                                CampusRepository $campusRepository,
                                EtatRepository $etatRepository): Response
    {
        //Part : 01
        //creation d'un date(sortie vide)
        $sortie = new Date();
        $sortie->setIdSortie($idUser);

        //verifie la condition que mon boutton enregister est activer
        if ($request->get("button")=="enregistre"){
                $sortie->setEtatSortie($etatRepository->find(1));
            }
            //regarde la valeur du boutton et si la valuer est publier
            elseif ($request->get("button")=="publier"){
                $sortie->setEtatSortie($etatRepository->find(2));
            }
            //si action sur boutton annuler
            elseif ($request->get("button")=="annuler"){
                return $this->redirectToRoute("app_home");
        }

        //instancie le formulaire avec CreerUneSortietuypes
        $sortieForm = $this->createForm(CreerUneSortieType::class,$sortie);

        //Part : 02
        //remplie le sortieform avec request
        $sortieForm->handleRequest($request);

        // Part : 03
        // --Tester si le form à des données envoyées et renregistrment dans la base de donnée
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
              //recuperation du campus dans grace a id recuperer sur twig
              $id_campus = $request->get('campus');
              $campus = $campusRepository->findOneBy(['id'=> $id_campus ]);
              $sortie->setCampus($campus);
              $sortie->setNbInscrit(1);

              //recuperation de utilisateur et inserstion dans sortie
              $user = $userRepository->findOneBy(['id'=>$idUser]);
              $sortie->setOrganisateur($user);
              $sortie->addParticipant($user);
              $entityManager->persist($sortie);
              $entityManager->flush();
            /************************************************************************************************************************/
            // version string format
            $this->addFlash("message_success", sprintf("La Sortie à été crée avec succès", $sortie->getNom()));

            /************************************************************************************************************************/
        // Redirection sur home
        return $this->redirectToRoute("app_recapAll");
        }

        return $this->render( 'sortie/formSortie.html.twig',["sortieForm"=> $sortieForm->createview(),
            'userSortie'=>$userRepository->find($idUser),
            'villeSortie'=>$villeRepository->findall() ,
            'lieuSortie'=>$lieuRepository->findall() ,
            ] );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/home", name="app_home_admin")
     */
    public function panelAdmin(): Response
    {

        return $this->render('pannelAdmin/pannelAdmin.html.twig');
    }



    /**
     * affichage une sortie
     */
    #[Route('/RecapSortie/{id_sortie}', name: 'app_recap_sortie')]
    public function recapSortie($id_sortie, DateRepository $dateRepository ): Response
    {
        return $this->render( 'sortie/recapSortie.html.twig',[ 'sortie'=>$dateRepository->find($id_sortie),
           ] );
    }



    /**
     * recpate de toutes les sorties
     */
    #[Route('/recapAll', name: 'app_recapAll')]
    public function recapAll(Request $request,FilterRegistration $filterRegistration,EtatRepository $etatRepository, DateRepository $dateRepository): Response
    {
        //Mise à jour etat liste
        $etatEnCours = $etatRepository->find(3);
        $etatPasser = $etatRepository->find(4);
        $etatFermer = $etatRepository->find(6);
        $etatArchiver = $etatRepository->find(7);
        $dateRepository->miseAjourEtat($etatFermer,$etatArchiver,$etatEnCours,$etatPasser);

        //instancie le formulaire avec CreerUneSortietuypes
        $recapForm = $this->createForm(RegistrationFormDateType::class);
        $recapForm->handleRequest($request);

        //appel de la fonction filtre global
        if ($this->getUser() != null) {
            if ($recapForm->isSubmitted() && $recapForm->isValid() && $this->getUser()) {

                // récupere les données du formulaires
                $user = $this->getUser();


                if (!is_null($recapForm->get('search')->getData())) {
                    $search = $recapForm->get('search')->getData();
                } else {
                    $search = -1;
                }

                $sortieInscrit = $recapForm->get('Sortieinscrit')->getData();
                $sortieNonInscrit = $recapForm->get('SortieNonInscrit')->getData();
                $sortiePassee = $recapForm->get('SortiePassees')->getData();
                $sortieOrganisateur = $recapForm->get('SortieOrganisateur')->getData();
                $dateStartRecupString = $request->get('dateStart');
                $dateFinRecupString = $request->get('dateFin');
                $dateStartRecup = new \DateTime($dateStartRecupString);
                $dateFinRecup = new DateTime($dateFinRecupString);
                $campusFlitre = $recapForm->get('campus')->getData();

                return $this->render('/sortie/recapAll.html.twig', ["RecapSortie" => $recapForm->createView(),
                    'listeSortie' => $filterRegistration
                        ->globalFilter($user, $search, $sortieNonInscrit, $sortieInscrit, $sortieOrganisateur, $sortiePassee, $campusFlitre, $dateStartRecup, $dateFinRecup),
                    'dateStart' => $dateStartRecup, 'dateFin' => $dateFinRecup,
                    'dateJour' => $dateJour = new DateTime(),
                    'etat'=> $etat = $etatRepository->findAll()

                ]);

            }


            //appel de la fonction search
            //if ($recapForm->isSubmitted() && $recapForm->isValid() && !is_null($request->get('search')) ) {
            //    $request->get('search');
            //    return $this->render('/sortie/recapAll.html.twig',["RecapSortie"=>$recapForm->createView(),
            //        'listeSortie'=>$filterRegistration->NameDateFilter( $recapForm->get('search')->getData())
//
            //        ]);
            //}

            // apple de la fonction affiche la date qui est passer
            //if ($recapForm->isSubmitted() && $recapForm->isValid()) {
            //    if ( $request->get('SortiePassees')){
            //        return $this->render('/sortie/recapAll.html.twig',["RecapSortie"=>$recapForm->createView(),
            //            'listeSortie'=>$filterRegistration-> DateFilterlast()
            //        ]);
            //    }
            //}
//        //apple de la fonction qui affiche suivant le campus
//        if ($recapForm->isSubmitted() && $recapForm->isValid()) {
//            //recupére la valuer du formulaire qui c'est afficher
//            $campusFlitre= $recapForm->get('campus')->getData();
//            return $this->render('/sortie/recapAll.html.twig',["RecapSortie"=>$recapForm->createView(),
//                'listeSortie'=>$filterRegistration-> DateCampus($campusFlitre)
//            ]);
//        }

            // appel de la fonction qui renvoi les sorties ou je suis inscrit
            //if ($recapForm->isSubmitted() && $recapForm->isValid()) {
            //    if ($recapForm->get('Sortieinscrit')->getData()) {
            //        $user = $this->getUser();
            //        return $this->render('/sortie/recapAll.html.twig', ["RecapSortie" => $recapForm->createView(),
            //            'listeSortie' => $filterRegistration->sortieInscrit($user)
//
            //        ]);
            //    }
            //}

            // appel de la fonction qui renvoi les sorties ou je ne suis pas inscrit
            //if ($recapForm->isSubmitted() && $recapForm->isValid()) {
            //    if ($recapForm->get('SortieNonInscrit')->getData()) {
            //        $user = $this->getUser();
            //        return $this->render('/sortie/recapAll.html.twig', ["RecapSortie" => $recapForm->createView(),
            //            'listeSortie' => $filterRegistration->sortieNonInscrit($user)
//
            //        ]);
            //    }
            //}

            //appel de la fonction qui renvoi les date de sortie comprise entre date debut et date fin
            if ($recapForm->isSubmitted() && $recapForm->isValid()) {
                //recupére la valuer du formulaire qui c'est afficher dateStart
                $dateStartRecupString = $request->get('dateStart');
                //recupére la valuer du formulaire qui c'est afficher datefin
                $dateFinRecupString = $request->get('dateFin');

                $dateStartRecup = new \DateTime($dateStartRecupString);
                $dateFinRecup = new DateTime($dateStartRecupString);
                return $this->render('/sortie/recapAll.html.twig', ["RecapSortie" => $recapForm->createView(),
                    'listeSortie' => $filterRegistration->startEndDate($dateStartRecup, $dateFinRecup),
                    'etat'=> $etat = $etatRepository->findAll(),
                    'dateJour' => $dateJour = new DateTime()]);

            }
        }
        $dateStartRecup = new DateTime();
        $dateFinRecup = new DateTime();
        $dateFinRecup->modify('+30 day');


        return $this->render( '/sortie/recapAll.html.twig',[ "RecapSortie"=> $recapForm->createview(),
            'listeSortie'=>$filterRegistration->DateFilterOpen(),'dateStart'=>$dateStartRecup ,'dateFin'=>$dateFinRecup,
            'dateJour'=> $dateJour = new DateTime(),
            'etat'=> $etat = $etatRepository->findAll(),
            'user'=> $user=-1
        ] );
    }

    /**********************************************************************************************************************/
    /**********************************************************************************************************************/

    /**
     * recpate de la sorties et posibiliter de la modiffier
     */
    #[Route('/modifierSortie/{id_sortie}', name: 'app_sortie_modifier', methods: ['GET'])]
    //affichage de des infornation de la sortie
    public function modifierSortie($id_sortie, Request $request,DateRepository $dateRepository, EtatRepository $etatRepository,
                                   CampusRepository $campusRepository, lieuRepository $lieuRepository, EntityManagerInterface $entityManager,): Response
    {

        //Part : 01
        //creation d'un date(sortie vide)
        $sortie = new Date();
        /************************************************************/
        //recupere les information de la sortie

        $sortie = $dateRepository->find($id_sortie);

        //verifie la condition que mon boutton enregister est activer
        if ($request->get("button") == "enregistre") {
            $sortie->setEtatSortie($etatRepository->find(1));
        } //regarde la valeur du boutton et si la valuer est publier
            if ($request->get("button") == "publier") {
                $sortie->setEtatSortie($etatRepository->find(2));
                return $this->redirectToRoute("app_home");
            } //regarde la valeur du boutton et si la valuer est supprimer
            elseif ($request->get("button") == "supprimer") {
                $sortie->setEtatSortie($etatRepository->find(6));
                return $this->redirectToRoute("app_home");
            } //si action sur boutton annuler
            elseif ($request->get("button") == "annuler") {
                return $this->redirectToRoute("app_home");
            }



        //

            //recuperation du campus dans grace a id recuperer sur twig
            $nameDate = $request->get('nameDate');
            $sortie->setNom($nameDate);

            //recuperation et mise ne base de l'heure je recupére un string que je le modifie en dateTime
            $dateStartRecupString = $request->get('timeStartDate');
            $dateFinRecupString = $request->get('timeEndDate');
            $dateStartRecup = new \DateTime($dateStartRecupString);
            $dateFinRecup = new DateTime($dateFinRecupString);
            $sortie->setDateHeureDebut($dateStartRecup);
            $sortie->setDateLimiteInscritpion($dateFinRecup);

            //recupération des monbre de place
            $nbPlace = $request->get('nbPlace');
            $sortie->setNbInscrit($nbPlace);

            //recupérer le temps pour mise a jour
            $nbTime = $request->get('nbTime');
            $sortie->setDuree($nbTime);


            //recuperation du campus et mise ne base de l'objet campus
//            $id_campus = $request->get('menuCampus');
//            $campus = $campusRepository->findOneBy(['id'=> $id_campus ]);
//            $sortie->setCampus($campus);

            //mise a jour dans la base de donner
            $entityManager->persist($sortie);
            $entityManager->flush();



        return $this->render('sortie/modifierSortie.html.twig', ['modifierSortie' => $dateRepository->find($id_sortie),
            'listecampus' => $campusRepository->findAll(), 'listelieu' => $lieuRepository->findall(),
        ]);
    }

    /**********************************************************************************************************************/
    /**********************************************************************************************************************/

    #[Route('/inscrireSortie/{id_sortie}', name: 'app_sortie_inscrire', methods: ['GET'])]
    public function inscrire($id_sortie, DateRepository $dateRepository, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        $sortie = $dateRepository->find($id_sortie);
        $user = $this->getUser();
        $participants = $sortie->getParticipants();
        if ($user instanceof $participants)
        {
            $this->addFlash("message_success", sprintf("Vous êtes déjà inscrit"));
            return $this->redirectToRoute('app_recapAll');
        }
        $sortie->setNbInscrit(1);
        if ($sortie->getNbInscrit()==$sortie->getNbInscritpionsMax())
        {
            $sortie->setEtatSortie($etatRepository->find(6));
            $sortie->addParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();


            return $this->redirectToRoute('app_recapAll');
        }
        $sortie->addParticipant($user);
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('app_recapAll');



    }

    #[Route('/desinscrireSortie/{id_sortie}', name: 'app_sortie_desinscrire', methods: ['GET'])]
    public function desinscrire($id_sortie,DateRepository $dateRepository,EntityManagerInterface $entityManager,EtatRepository $etatRepository): Response
    {
        $sortie = $dateRepository->find($id_sortie);
        $user = $this->getUser();
        if ($sortie->getParticipants()->contains($user))
        {
            $sortie->setNbInscrit(-1);
            if ($sortie->getNbInscrit()<$sortie->getNbInscritpionsMax()) {
                $sortie->setEtatSortie($etatRepository->find(2));
                $sortie->removeParticipant($user);
                $entityManager->persist($sortie);
                $entityManager->flush();
                return $this->redirectToRoute('app_recapAll');
            }
            $sortie->removeParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_recapAll');

        }

        $this->addFlash("message_success", sprintf("Vous ne participez pas à cet évènement"));
        return $this->redirectToRoute('app_recapAll');
    }



    #[Route('/new', name: 'app_lieu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LieuRepository $lieuRepository): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuRepository->add($lieu, true);

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

/**********************************************************************************************************************/

    #[Route('modifierSortie/apiLieu/{lieu}', name: 'app_api', methods: ['GET', 'POST'])]
    //EntityManagerInterface $entityManager permet de créer une requette sql
    //{lieu} doit etre identique a  $lieu dans la bare de modifierSortie/api/1 il va cherche l'objet 1 de la base
    public function apiLieu(Lieu $lieu, VilleRepository $villeRepository): Response
    {
        $lieuApi=[
            'id'=>$lieu->getId(),
            'nom'=>$lieu->getNom(),
            'rue'=>$lieu->getRue(),
            'latitude'=>$lieu->getLatitude(),
            'longitude'=>$lieu->getLongitude(),
        ];
        return new JsonResponse($lieuApi);
    }

    #[Route('/annulerSortie/{id_sortie}', name: 'app_sortie_annuler', methods: ['GET'])]
    public function annuler($id_sortie): Response
    {
        return $this->render('sortie/annulerSortie.html.twig', [
            'search' => $id_sortie,
        ]);
    }





}

