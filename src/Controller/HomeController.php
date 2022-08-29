<?php

namespace App\Controller;

use App\Form\RegistrationFormDateType;//imprtation du formulaire Registartion
use App\Repository\CampusRepository;
use App\Repository\DateRepository;
use App\Repository\EtatRepository;
use App\Repository\FilterRegistration;
use App\Repository\FilterRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Date; //import l'Entité Date
use App\Entity\User; //import l'entité User



use App\Form\CreerUneSortieType; //importation du formulaire CreeUneSortie


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }




    /**
    * affichage du formulaire vide
     */
    #[Route('/CreerSortie/{idUser}', name: 'app_sortie')]
    public function CreerSortie($idUser,
                                Request $request,
                                EntityManagerInterface $entityManager,
                                UserRepository $userRepository ,
                                VilleRepository $villeRepository ,
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
                $sortie->setEtat(1);
                $sortie->setEtatSortie($etatRepository->find(1));
            }
            //regarde la valeur du boutton et si la valuer est publier
            elseif ($request->get("button")=="publier"){
                $sortie->setEtat(2);
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

              //recuperation de utilisateur et inserstion dans sortie
              $user = $userRepository->findOneBy(['id'=>$idUser]);
              $sortie->setOrganisateur($user);

              $entityManager->persist($sortie);
              $entityManager->flush();
            /************************************************************************************************************************/
            // version string format
            $this->addFlash("message_success", sprintf("La Sortie à été crée avec succès", $sortie->getNom()));

            /************************************************************************************************************************/
        // Redirection sur home
        return $this->redirectToRoute("app_home");
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
        return $this->render( 'sortie/recapSortie.html.twig',[ 'userDate'=>$dateRepository->find($id_sortie),
           ] );
    }



    /**
     * recpate de toutes les sorties
     */
    #[Route('/recapAll', name: 'app_recapAll')]
    public function recapAll(Request $request,FilterRegistration $filterRegistration ,DateRepository $dateRepository): Response
    {
        //instancie le formulaire avec CreerUneSortietuypes
        $recapForm = $this->createForm(RegistrationFormDateType::class);
        $recapForm->handleRequest($request);
        dd($recapForm);
        //appel de la fonction search
        if ($recapForm->isSubmitted() && $recapForm->isValid()) {
            $recherche = $request->get('search');
            return $this->render('/sortie/recapAll.html.twig',["RecapSortie"=>$recapForm->createView(),
                'listeSortieOuverte'=>$filterRegistration->NameDateFilter( $recapForm->get('search')->getData())

                ]);
        }

        // apple de la fonction affiche la date qui est passer
        if ($recapForm->isSubmitted() && $recapForm->isValid()) {
            $recherche = $request->get('SortiePassees');
            return $this->render('/sortie/recapAll.html.twig',["RecapSortie"=>$recapForm->createView(),
                'listeSortieOuverte'=>$filterRegistration-> DateFilterlast()

            ]);
        }




        return $this->render( '/sortie/recapAll.html.twig',[ "RecapSortie"=> $recapForm->createview(),
            'listeSortieOuverte'=>$filterRegistration->DateFilterOpen()
        ] );
    }
    #[Route('/annulerSortie/{id_sortie}', name: 'app_annuler_show', methods: ['GET'])]
    public function show($id_sortie): Response
    {
        return $this->render('sortie/annulerSortie.html.twig', [
            'search' => $id_sortie,
        ]);
    }

}
