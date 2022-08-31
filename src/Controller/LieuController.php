<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\FilterLieuType;
use App\Form\LieuType;
use App\Repository\FilterLieuRepository;
use App\Repository\LieuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[IsGranted("ROLE_ADMIN")]
#[Route('/lieu')]
class LieuController extends AbstractController
{
    #[Route('/', name: 'app_lieu_index', methods: ['GET', 'POST'])]
    public function index(FilterLieuRepository $filterLieuRepository,LieuRepository $lieuRepository , Request $request): Response
    {
        $form = $this->createForm(FilterLieuType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recherche = $request->get('nom');
            //dd($form->get('nom')->getData());
            //dd($filterCampusRepository->CampusFilter($form->get('nom')->getData()));

            return $this->render( 'lieu/index.html.twig',["FilterLieuType"=> $form->createview(),
                'lieus' => $filterLieuRepository->LieuFilter($form->get('nom')->getData()),
            ]);
        }
        return $this->render('lieu/index.html.twig',["FilterLieuType"=> $form->createview(),
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_show', methods: ['GET'])]
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lieu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lieu $lieu, LieuRepository $lieuRepository): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuRepository->add($lieu, true);

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_delete', methods: ['POST'])]
    public function delete(Request $request, Lieu $lieu, LieuRepository $lieuRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $lieuRepository->remove($lieu, true);
        }

        return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
    }

}
