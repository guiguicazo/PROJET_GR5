<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FilterType;
use App\Form\ProfilUserType;
use App\Form\UserType;
use App\Repository\FilterRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Security\AppAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;


//Routre pour montrer le profil de l'utilisateur
//#[Route('/user/{id}', name: 'app_showUser_index')]
class ShowUserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_showuser_show', methods: ['GET'])]
    public function show($id,UserRepository $userRepository,Request $request): Response
    {

        $user = $userRepository->find($id);


        return $this->render('user/showUser.html.twig', [
            'user' => $user,
        ]);
    }
}


