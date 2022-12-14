<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Form\ConsultantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminConsultantController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/consultant', name: 'app_admin_consultant')]
    public function index(): Response
    {
        $consultants = $this->entityManager->getRepository(User::class)->findUsersByRole('ROLE_CONSULTANT');

        return $this->render('admin/consultant.html.twig', [
            'consultants' => $consultants
        ]);
    }

    #[Route('/admin/consultant/creer', name: 'app_admin_consultant_create')]
    public function create(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $error = null;

        $consultant = new User();

        $form = $this->createForm(ConsultantType::class, $consultant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search_email =$this->entityManager->getRepository(User::class)->findOneByEmail($consultant->getEmail());
            if (!$search_email) {
                //Ajout du rôle ROLE_CONSULTANT
                $consultant->setRoles(['ROLE_CONSULTANT']);

                //Activation du compte
                $consultant->setIs_approved(true);

                //Hashage du mot de passe                
                $password = $hasher->hashPassword($consultant, $consultant->getPassword());
                $consultant->setPassword($password);

                $this->entityManager->persist($consultant);
                $this->entityManager->flush();
                $this->addFlash("success", "Le consultant {$consultant->getFirstname()} {$consultant->getLastname()} a été créé correctement.");

                return $this->redirectToRoute('app_admin_consultant');
            } else {
                $error = "L'email que vous avez renseigné existe déjà.";
            }
        }

        return $this->render('admin/consultant_form.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    #[Route('/admin/consultant/{id}/editer', name: 'app_admin_consultant_edit')]
    public function edit(Request $request, int $id, UserPasswordHasherInterface $hasher): Response
    {
        $error = null;

        $consultant = $this->entityManager->getRepository(User::class)->findOneById($id);

        if (!$consultant) {
            return $this->redirectToRoute('app_admin_consultant');
        }

        $actualEmail = $consultant->getEmail();

        $form = $this->createForm(ConsultantType::class, $consultant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search_email =$this->entityManager->getRepository(User::class)->findOneByEmail($consultant->getEmail());
            if (!$search_email || $search_email->getEmail() === $actualEmail) {
                //Hashage du mot de passe                
                $password = $hasher->hashPassword($consultant, $consultant->getPassword());
                $consultant->setPassword($password);

                $this->entityManager->flush();
                $this->addFlash("success", "Le consultant {$consultant->getFirstname()} {$consultant->getLastname()} a été modifié correctement.");

                return $this->redirectToRoute('app_admin_consultant');
            } else {
                $error = "L'email que vous avez renseigné existe déjà.";                
            }
        }

        return $this->render('admin/consultant_form.html.twig', [
            'form' => $form->createView(),
            'consultant' => $consultant,
            'error' => $error
        ]);
    }

    #[Route('/admin/consultant/{id}/supprimer', name: 'app_admin_consultant_delete')]
    public function delete(int $id): Response
    {
        $error = $success = null;

        $consultant = $this->entityManager->getRepository(User::class)->findOneById($id);
        if ($consultant) {
            $this->entityManager->remove($consultant);
            $this->entityManager->flush();
            $this->addFlash("success", "Le consultant {$consultant->getFirstname()} {$consultant->getLastname()} a bien été supprimé.");
        } else {
            $this->addFlash("danger", "Le consultant {$consultant->getFirstname()} {$consultant->getLastname()} n'a pas pu être supprimé.");
        }

        return $this->redirectToRoute('app_admin_consultant');
    }
}
