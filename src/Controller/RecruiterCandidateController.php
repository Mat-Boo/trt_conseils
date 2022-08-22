<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterCandidateController extends AbstractController
{   
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;   
    }

    #[Route('/consultant/{type}', name: 'app_recruiter_candidate')]
    public function index(string $type): Response
    {
        $translateType = '';
        if ($type === 'candidat') {
            $translateType = 'candidate';
        } elseif ($type === 'recruteur') {
            $translateType = 'recruiter';
        }

        $users = $this->entityManager->getRepository(User::class)->findUsersByRole('ROLE_' . strtoupper($translateType));
        return $this->render('consultant/recruiter_candidate.html.twig', [
            'users' => $users,
            'type' => $type
        ]);
    }
}
