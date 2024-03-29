<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\JobOffer;
use App\Form\JobOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobOfferController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManqager)
    {
        $this->entityManager = $entityManqager;
    }

    #[Route('/offres-emploi', name: 'app_job_offers')]
    public function index(): Response
    {
        $approvedJobOffersNonPostulated = [];

        $approvedJobOffers = $this->entityManager->getRepository(JobOffer::class)->findApprovedJobOffers();
        
        foreach($approvedJobOffers as $jobOffer) {
            $candidatesPerJob = [];
            if (!$jobOffer->getCandidatures()->isEmpty()) {
                foreach ($jobOffer->getCandidatures() as $candidature) {
                    $candidatesPerJob[] = $candidature->getCandidate();
                }
                if (!in_array($this->getUser(), $candidatesPerJob)) {
                    $approvedJobOffersNonPostulated[] = $jobOffer;
                }
            } else {
                $approvedJobOffersNonPostulated[] = $jobOffer;
            }
        }

        return $this->render('job_offer/index.html.twig', [
            'approvedJobOffersNonPostulated' => $approvedJobOffersNonPostulated
        ]);
    }    

    #[Route('/candidat/offre-emploi/{id}/postuler', name: 'app_job_offer_apply')]
    public function applyFor(int $id): Response
    {
        $candidature = new Candidature();
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);
        if ($jobOffer && $this->getUser()) {
            $candidature->setCandidate($this->getUser());
            $candidature->setJobOffer($jobOffer);
            $this->entityManager->persist($candidature);
            $this->entityManager->flush();

            $this->addFlash("success", "Votre demande de candidature a bien été prise en compte. Nous vous informerons prochainement par mail si votre candidature a été validée ou refusée.");
            return $this->redirectToRoute('app_job_offers');
        }
    }

}
