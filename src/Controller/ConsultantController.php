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

    #[Route('/consultant/offre-emploi/{id}/approuver', name: 'app_consultant_job_offer_approve')]
    public function approve(int $id): Response
    {
        $success = null;

        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);

        $jobOffer->setIs_approved(true);
        $this->entityManager->flush();
        $success = 'L\'offre d\'emploi ' . $id . ' de la société ' . $jobOffer->getRecruiter()->getCompany() . ' a bien été approuvée.';

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/offre-emploi/{id}/refuser', name: 'app_consultant_job_offer_refuse')]
    public function refuse(): Response
    {
        $success = null;

        return $this->render('consultant/index.html.twig', [
            'success' => $success
        ]);
    }
}
