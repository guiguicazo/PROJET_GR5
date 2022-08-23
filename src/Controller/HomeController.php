<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Date; //import l'Entité Date

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
    #[Route('/CreerSortie', name: 'app_sortie')]
    public function CreerSortie(Request $request): Response
    {
        //Part : 01
        //creation d'un date(sortie vide)
        $sortie = new Date();

        //instancie le formulaire avec CreerUneSortietuypes
        $sortieForm = $this->createForm(CreerUneSortieType::class,$sortie);

        //Part : 02
        //remplie le sortieform avec request
        $sortieForm->handleRequest($request);

        // Part : 03
        // --Tester si le form à des données envoyées



        return $this->render( 'sortie/formSortie.html.twig',["sortieForm"=> $sortieForm->createview()] );
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/home", name="app_home_admin")
     */
    public function panelAdmin(): Response
    {

        // je force la redirection sur la route app_home
        return new Response("Je suis dans la page admin");
    }

}
