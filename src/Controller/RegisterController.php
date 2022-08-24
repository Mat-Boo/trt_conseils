<?php

namespace App\Controller;

use App\Class\Mail;
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
                //Ajout du rôle ROLE_CANDIDATE
                $user->setRoles(['ROLE_CANDIDATE']);

                //Hashage du mot de passe                
                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                
                //Déplacement du CV uploadé dans le dossier public/uploads
                $cv = $formCanditate->get('cv')->getData();
                $newCvName = md5(uniqid()) . '.pdf';
                $cv->move($this->getParameter('files_directory'), $newCvName); //file_directory paramétré dans le fichier config/services.yaml
                $user->setCv($newCvName);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                $success = "Votre inscription s'est correctement déroulée. Lorsque votre compte sera actif, vous recevrez un mail à l'adresse suivante : {$user->getEmail()}.";
                
                //Envoie d'un mail au candidat nouvellement inscrit pour prise en compte de son inscription
                $mail = new Mail();
                $contentMail = "Bonjour {$user->getFirstname()},<br/><br/>";
                $contentMail .= "Nous avons bien pris en compte votre demande d'inscription auprès de nos services.<br/>";
                $contentMail .= "Nous reviendrons rapidement vers vous pour valider cette demande.<br/><br/>";
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'TRT Conseils - Inscription', $contentMail);

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
                //Ajout du rôle ROLE_RECRUITER
                $user->setRoles(['ROLE_RECRUITER']);

                //Hashage du mot de passe
                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $success = "Votre inscription s'est correctement déroulée. Vous recevrez un mail lorsque votre compte sera actif.";

                //Envoie d'un mail au recruteur nouvellement inscrit pour prise en compte de son inscription
                $mail = new Mail();
                $contentMail = "Bonjour,<br/><br/>";
                $contentMail .= "Nous avons bien pris en compte votre demande d'inscription auprès de nos services.<br/>";
                $contentMail .= "Nous reviendrons rapidement vers vous pour valider cette demande.<br/><br/>";
                $mail->send($user->getEmail(), $user->getCompany(), 'TRT Conseils - Inscription', $contentMail);

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
