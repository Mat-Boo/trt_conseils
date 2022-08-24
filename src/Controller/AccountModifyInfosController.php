<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterCandidateType;
use App\Form\RegisterRecruiterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountModifyInfosController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-infos', name: 'app_account_modify_infos')]
    public function index(Request $request): Response
    {

        $error = null;
        
        $user = $this->getUser();
        if (!$user || $user !== $this->getUser()) {
            return $this->redirectToRoute('app_account_infos');
        }

        $actualCvName = $user->getCv();
        $actualEmail = $user->getEmail();

        $formCanditate = $this->createForm(RegisterCandidateType::class, $user);
        $formCanditate->handleRequest($request);
        if ($formCanditate->isSubmitted() && $formCanditate->isValid()) {
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email || $search_email->getEmail() === $actualEmail) {
                //Déplacement du CV uploadé dans le dossier public/uploads
                $cv = $formCanditate->get('cv')->getData();
                if ($cv) {
                    $newCvName = md5(uniqid()) . '.pdf';
                    $cv->move($this->getParameter('files_directory'), $newCvName); //file_directory paramétré dans le fichier config/services.yaml
                    $user->setCv($newCvName);
                } else {
                    $user->setCv($actualCvName);
                }
                $this->entityManager->flush();
                $this->addFlash("success", "Vos informations personnelles ont correctement été mises à jour.");
                return $this->redirectToRoute('app_account_infos');
            } else {
                $error = "L'email que vous avez renseigné existe déjà.";
                $user->setEmail($actualEmail);
            }
        }

        $formRecruiter = $this->createForm(RegisterRecruiterType::class, $user);
        $formRecruiter->handleRequest($request);
        if ($formRecruiter->isSubmitted() && $formRecruiter->isValid()) {
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email || $search_email->getEmail() === $actualEmail) {
                $this->entityManager->flush();
                $this->addFlash("success", "Vos informations personnelles ont correctement été mises à jour.");
                return $this->redirectToRoute('app_account_infos');
            } else {
                $error = "L'email que vous avez renseigné existe déjà.";
                $user->setEmail($actualEmail);
            }
        }

        return $this->render('account/modify_infos.html.twig', [
            'formCandidate' => $formCanditate->createView(),
            'formRecruiter' => $formRecruiter->createView(),
            'error' => $error
        ]);
    }
}
