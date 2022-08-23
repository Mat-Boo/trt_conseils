<?php

namespace App\Controller;

use App\Entity\Candidature;
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
        $nonApprovedCandidatures = $this->entityManager->getRepository(Candidature::class)->findNonApprovedCandidatures();

        $nonApprovedJobOffers = $this->entityManager->getRepository(JobOffer::class)->findNonApprovedJobOffers();

        $nonApprovedCandidates = $this->entityManager->getRepository(User::class)->findNonApprovedUsersByRole('ROLE_CANDIDATE');

        $nonApprovedRecruiters = $this->entityManager->getRepository(User::class)->findNonApprovedUsersByRole('ROLE_RECRUITER');

        return $this->render('consultant/index.html.twig', [
            'nonApprovedCandidatures' => $nonApprovedCandidatures,
            'nonApprovedJobOffers' => $nonApprovedJobOffers,
            'nonApprovedCandidates' => $nonApprovedCandidates,
            'nonApprovedRecruiters' => $nonApprovedRecruiters
        ]);
    }

    #[Route('/consultant/candidature/{id}/approuver', name: 'app_consultant_candidature_approve')]
    public function approveCandidature(int $id): Response
    {
        $success = null;

        $candidature = $this->entityManager->getRepository(Candidature::class)->findOneById($id);

        $candidature->setIsApproved(true);
        $this->entityManager->flush();
        $success = 'La candidature ' . $id . ' de ' . $candidature->getCandidate()->getFirstname() . ' ' . $candidature->getCandidate()->getLastname() . ' pour l\offre de ' . $candidature->getJobOffer()->getTitle() . ' a bien été approuvée.';

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/offre-emploi/{id}/approuver', name: 'app_consultant_job_offer_approve')]
    public function approveJobOffer(int $id): Response
    {
        $success = null;

        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);

        $jobOffer->setIs_approved(true);
        $this->entityManager->flush();
        $success = 'L\'offre d\'emploi ' . $id . ' de la société ' . $jobOffer->getRecruiter()->getCompany() . ' a bien été approuvée.';

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/candidat/{id}/approuver', name: 'app_consultant_candidate_approve')]
    public function approveCandidate(int $id): Response
    {
        $success = null;

        $candidate = $this->entityManager->getRepository(User::class)->findOneById($id);

        $candidate->setIs_approved(true);
        $this->entityManager->flush();
        $success = 'Le candidat ' . $candidate->getFirstname() . ' ' . $candidate->getLastname() . ' a bien été approuvé.';

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/recruteur/{id}/approuver', name: 'app_consultant_recruiter_approve')]
    public function approveRecruiter(int $id): Response
    {
        $success = null;

        $recruiter = $this->entityManager->getRepository(User::class)->findOneById($id);

        $recruiter->setIs_approved(true);
        $this->entityManager->flush();
        $success = 'La sociéte de recrutement ' . $recruiter->getCompany() . ' a bien été approuvé.';

        return $this->redirectToRoute('app_consultant');
    }
}
