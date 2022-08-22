<?php

namespace App\Controller;

use App\Form\ModifyPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountModifyPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $success = null;
        $error = null;

        $user = $this->getUser();
        $form = $this->createForm(ModifyPasswordType::class, $user);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            if ($hasher->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_pwd);
                $user->setPassword($password);
                $this->entityManager->flush();
                $success = 'Votre mot de passe a bien été mis à jour.';
                return $this->redirectToRoute('app_account_infos');
            } else {
                $error = 'Votre mot de passe actuel est incorrect.';
            }
        }

        return $this->render('account/modify_password.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
            'error' => $error
        ]);
    }
}
