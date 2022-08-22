<?php

namespace App\Controller;

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
        $jobOffers = $this->entityManager->getRepository(JobOffer::class)->findAll();

        return $this->render('job_offer/index.html.twig', [
            'jobOffers' => $jobOffers
        ]);
    }

    #[Route('/recruteur/mes-offres-emploi', name: 'app_my_job_offers')]
    public function myOffers(): Response
    {
        return $this->render('job_offer/myOffers.html.twig');
    }

    

    #[Route('/candidat/offre-emploi/{id}/postuler', name: 'app_job_offer_apply')]
    public function applyFor(int $id): Response
    {
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);
        if ($jobOffer && $this->getUser()) {
            $jobOffer->addCandidate(($this->getUser()));
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_job_offers');
    }

}
