<?php

namespace App\Controller;

use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAppliedJobOffersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/offres-emploi-postulees', name: 'app_account_applied_job_offers')]
    public function index(): Response
    {
        $postulatedNonApprovedJobOffers = [];

        $nonApprovedCandidatures = $this->entityManager->getRepository(Candidature::class)->findNonApprovedCandidatures();
        foreach($nonApprovedCandidatures as $nonApprovedCandidature) {
            if ($nonApprovedCandidature->getCandidate() === $this->getUser()) {
                $postulatedNonApprovedJobOffers[] = $nonApprovedCandidature->getJobOffer();
            }
        }

        $appliedJobOffers = [];

        $approvedCandidatures = $this->entityManager->getRepository(Candidature::class)->findApprovedCandidatures();
        foreach($approvedCandidatures as $approvedCandidature) {
            if ($approvedCandidature->getCandidate() === $this->getUser()) {
                $appliedJobOffers[] = $approvedCandidature->getJobOffer();
            }
        }
        
        return $this->render('account/applied_job_offers.html.twig', [
            'postulatedNonApprovedJobOffers' => $postulatedNonApprovedJobOffers,
            'appliedJobOffers' => $appliedJobOffers
        ]);
    }
}
