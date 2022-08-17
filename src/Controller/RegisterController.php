<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterCandidateType;
use App\Form\RegisterRecruiterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManqager)
    {
        $this->entityManager = $entityManqager;
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $keyPost = null;
        if (!empty($_POST)) {
            $keyPost = array_key_first($_POST);
        }

        $error = $success = null;

        $user = new User();
        
        $formCanditate = $this->createForm(RegisterCandidateType::class, $user);
        $formCanditate->handleRequest($request);
        if ($formCanditate->isSubmitted() && $formCanditate->isValid()) {
            $user = $formCanditate->getData();
            $search_email =$this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email) {
                $user->setRoles(['ROLE_CANDIDATE']);

                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                $success = "Votre inscription s'est correctement déroulée. Vous recevrez un mail lorsque votre compte sera actif.";
            } else {
                $error = "L'email que vous avez renseigné existe déjà.";                
            }
        }

        $formRecruiter = $this->createForm(RegisterRecruiterType::class, $user);
        $formRecruiter->handleRequest($request);
        if ($formRecruiter->isSubmitted() && $formRecruiter->isValid()) {
            $user = $formRecruiter->getData();
            $search_email =$this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email) {
                
                $user->setRoles(['ROLE_RECRUITER']);

                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $success = "Votre inscription s'est correctement déroulée. Vous recevrez un mail lorsque votre compte sera actif.";
            } else {
                $error = "L'email que vous avez renseigné existe déjà.";                
            }
        }


        return $this->render('register/index.html.twig', [
            'formCandidate' => $formCanditate->createView(),
            'formRecruiter' => $formRecruiter->createView(),
            'error' => $error,
            'success' => $success,
            'keyPost' => $keyPost
        ]);
    }
}
