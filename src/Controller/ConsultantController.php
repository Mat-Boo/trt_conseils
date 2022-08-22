<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/consultant', name: 'app_consultant')]
    public function index(): Response
    {

        $nonApprovedJobOffers = $this->entityManager->getRepository(JobOffer::class)->findNonApprovedJobOffers();

        $nonApprovedCandidates = $this->entityManager->getRepository(User::class)->findNonApprovedUsersByRole('ROLE_CANDIDATE');

        $nonApprovedRecruiters = $this->entityManager->getRepository(User::class)->findNonApprovedUsersByRole('ROLE_RECRUITER');

        return $this->render('consultant/index.html.twig', [
            'nonApprovedJobOffers' => $nonApprovedJobOffers,
            'nonApprovedCandidates' => $nonApprovedCandidates,
            'nonApprovedRecruiters' => $nonApprovedRecruiters
        ]);
    }
}
