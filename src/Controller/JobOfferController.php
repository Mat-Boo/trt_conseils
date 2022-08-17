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

    #[Route('/candidat/offres-emploi', name: 'app_job_offers')]
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

    #[Route('/recruteur/offre-emploi/creer', name: 'app_job_offer_create')]
    public function create(Request $request): Response
    {
        $jobOffer = new JobOffer();

        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setRecruiter(($this->getUser()));
            $this->entityManager->persist($jobOffer);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_my_job_offers');
        }


        return $this->render('job_offer/job_offer_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/recruteur/offre-emploi/{id}/editer', name: 'app_job_offer_edit')]
    public function edit(Request $request, int $id): Response
    {
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);
        if (!$jobOffer || $jobOffer->getRecruiter() !== $this->getUser()) {
            return $this->redirectToRoute('app_my_job_offers');
        }

        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_my_job_offers');
        }

        return $this->render('job_offer/job_offer_form.html.twig', [
            'form' => $form->createView(),
            'jobOffer' => $jobOffer
        ]);
    }

    #[Route('/recruteur/offre-emploi/{id}/supprimer', name: 'app_job_offer_delete')]
    public function delete(int $id): Response
    {
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);
        if ($jobOffer && $jobOffer->getRecruiter() == $this->getUser()) {
            $this->entityManager->remove($jobOffer);
            $this->entityManager->flush();
        }
        
        return $this->redirectToRoute('app_my_job_offers');
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
