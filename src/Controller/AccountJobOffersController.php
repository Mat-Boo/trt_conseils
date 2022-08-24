<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountJobOffersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/mes-offres-emploi', name: 'app_account_job_offers')]
    public function index(): Response
    {
        $approvedJobOffers = 0;
        $nonApprovedJobOffers = 0;

        $jobOffers = $this->entityManager->getRepository(JobOffer::class)->findAll();
        foreach($jobOffers as $jobOffer) {
            if ($jobOffer->getRecruiter() === $this->getUser()) {
                if ($jobOffer->isIs_approved() === false) {
                    $nonApprovedJobOffers++;
                } elseif ($jobOffer->isIs_approved() === true) {
                    $approvedJobOffers++;
                }
            }
        }

        return $this->render('account/job_offers.html.twig', [
            'approvedJobOffers' => $approvedJobOffers,
            'nonApprovedJobOffers' => $nonApprovedJobOffers
        ]);
    }

    #[Route('/compte/offre-emploi/creer', name: 'app_account_job_offer_create')]
    public function create(Request $request): Response
    {
        $jobOffer = new JobOffer();

        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setRecruiter($this->getUser());
            $this->entityManager->persist($jobOffer);
            $this->entityManager->flush();
            $this->addFlash("success", "Votre offre d'emploi a bien été créée.");
            return $this->redirectToRoute('app_account_job_offers');
        }


        return $this->render('account/job_offer_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/offre-emploi/{id}/editer', name: 'app_account_job_offer_edit')]
    public function edit(Request $request, int $id): Response
    {
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);
        if (!$jobOffer || $jobOffer->getRecruiter() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_job_offers');
        }

        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash("success", "Votre offre d'emploi $id a bien été modifiée.");
            return $this->redirectToRoute('app_account_job_offers');
        }

        return $this->render('account/job_offer_form.html.twig', [
            'form' => $form->createView(),
            'jobOffer' => $jobOffer
        ]);
    }

    #[Route('/compte/offre-emploi/{id}/supprimer', name: 'app_account_job_offer_delete')]
    public function delete(int $id): Response
    {
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);
        if ($jobOffer && $jobOffer->getRecruiter() == $this->getUser()) {
            $this->entityManager->remove($jobOffer);
            $this->entityManager->flush();
            $this->addFlash("success", "Votre offre d'emploi $id a bien été supprimée.");
        }
        
        return $this->redirectToRoute('app_account_job_offers');
    }
}