<?php

namespace App\Controller;

use App\Class\Mail;
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
        $candidature = $this->entityManager->getRepository(Candidature::class)->findOneById($id);

        if ($candidature) {
            $candidature->setIsApproved(true);
            $this->entityManager->flush();
            $this->addFlash("success", "La candidature de {$candidature->getCandidate()->getFirstname()} {$candidature->getCandidate()->getLastname()} pour l'offre de {$candidature->getJobOffer()->getTitle()} a bien ??t?? approuv??e.");
        }

        //Envoie d'un mail au candidat pour le pr??venir de la validation de sa candidature
        $mail = new Mail();
        $contentMail = "Bonjour {$candidature->getCandidate()->getFirstname()},<br/><br/>";
        $contentMail .= "Votre candidature pour le poste de {$candidature->getJobOffer()->getTitle()} a bien ??t?? prise en compte.<br/>";
        $contentMail .= "La soci??t?? {$candidature->getJobOffer()->getRecruiter()->getCompany()} dispose dor??navant de toutes vos coordonn??es et reviendra vers vous directement.<br/>";
        $contentMail .= "Merci d'avoir choisi TRT Conseils.<br/><br/>";
        $mail->send($candidature->getCandidate()->getEmail(), $candidature->getCandidate()->getFirstname() . ' ' . $candidature->getCandidate()->getLastname(), 'TRT Conseils - Candidature valid??e', $contentMail);

        //Envoie d'un mail au recruteur pour le pr??venir de la nouvelle candidature
        $mail = new Mail();
        $contentMail = "Bonjour,<br/><br/>";
        $contentMail .= "Une nouvelle candidature pour le poste de {$candidature->getJobOffer()->getTitle()} a ??t?? ajout??e ?? cette offre.<br/>";
        $contentMail .= "Vous pouvez retrouver toutes les informations du candidat sur l'offre concern??e directement sur votre espace depuis la rubrique 'Mon compte'.<br/>";
        $contentMail .= "Voici un lien direct vers le CV du candidat {$candidature->getCandidate()->getFirstname()} {$candidature->getCandidate()->getLastname()}: <br/>";
        $contentMail .= "<a href=https://trt-conseils-studi.herokuapp.com/uploads/" . $candidature->getCandidate()->getCv() . ">T??l??charger le CV</a><br/>";
        $contentMail .= "Merci d'avoir choisi TRT Conseils.<br/><br/>";
        $mail->send($candidature->getJobOffer()->getRecruiter()->getEmail(), $candidature->getJobOffer()->getRecruiter()->getCompany(), 'TRT Conseils - Nouvelle candidature', $contentMail);

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/candidature/{id}/refuser', name: 'app_consultant_candidature_refuse')]
    public function refuseCandidature(int $id): Response
    {
        $candidature = $this->entityManager->getRepository(Candidature::class)->findOneById($id);

        if ($candidature) {
            $this->entityManager->remove($candidature);
            $this->entityManager->flush();
            $this->addFlash("success", "La candidature de {$candidature->getCandidate()->getFirstname()} {$candidature->getCandidate()->getLastname()} pour l'offre de {$candidature->getJobOffer()->getTitle()} a bien ??t?? refus??e.");
        }
        

        //Envoie d'un mail au candidat pour le pr??venir du refus de sa candidature
        $mail = new Mail();
        $contentMail = "Bonjour {$candidature->getCandidate()->getFirstname()},<br/><br/>";
        $contentMail .= "Votre candidature pour le poste de {$candidature->getJobOffer()->getTitle()} n'a malheureusement pas ??t?? approuv??e.<br/>";
        $contentMail .= "Pour plus d'information, vous pouvez contacter TRT Conseils par mail ?? l'adresse suivante : contact@trt-conseil.com<br/>";
        $contentMail .= "Merci d'avoir choisi TRT Conseils.<br/><br/>";
        $mail->send($candidature->getCandidate()->getEmail(), $candidature->getCandidate()->getFirstname() . ' ' . $candidature->getCandidate()->getLastname(), 'TRT Conseils - Candidature refus??e', $contentMail);

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/offre-emploi/{id}/approuver', name: 'app_consultant_job_offer_approve')]
    public function approveJobOffer(int $id): Response
    {
        $jobOffer = $this->entityManager->getRepository(JobOffer::class)->findOneById($id);

        $jobOffer->setIs_approved(true);
        $this->entityManager->flush();
        $this->addFlash("success", "L'offre d'emploi $id de la soci??t?? {$jobOffer->getRecruiter()->getCompany()} a bien ??t?? approuv??e.");

        //Envoie d'un mail au recruteur pour le pr??venir de la validation de son offre d'emploi
        $mail = new Mail();
        $contentMail = "Bonjour,<br/><br/>";
        $contentMail .= "Votre offre d'emploi pour le poste de {$jobOffer->getTitle()} a bien ??t?? valid??e.<br/>";
        $contentMail .= "Elle est dor??navant visible dans l'espace des candidats.<br/>";
        $contentMail .= "Vous recevrez un email ?? chaque candidature valid??e par nos ??quipes pour cette offre et vous retrouverez facilement cette offre et les informations des candidats directement sur votre espace depuis la rubrique 'Mon compte'<br/><br/>";
        $mail->send($jobOffer->getRecruiter()->getEmail(), $jobOffer->getRecruiter()->getCompany(), 'TRT Conseils - Offre d\'emploi valid??e', $contentMail);

        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/candidat/{id}/approuver', name: 'app_consultant_candidate_approve')]
    public function approveCandidate(int $id): Response
    {
        $candidate = $this->entityManager->getRepository(User::class)->findOneById($id);

        $candidate->setIs_approved(true);
        $this->entityManager->flush();
        $this->addFlash("success", "Le candidat {$candidate->getFirstname()} {$candidate->getLastname()} a bien ??t?? approuv??.");

        //Envoie d'un mail au candidat pour le pr??venir de la validation de son compte et donc la possibilit?? de se connecter
        $mail = new Mail();
        $contentMail = "Bonjour {$candidate->getFirstname()},<br/><br/>";
        $contentMail .= "Votre compte a bien ??t?? valid?? par nos ??quipes.<br/>";
        $contentMail .= "Vous pouvez d??sormais vous connecter depuis la rubrique 'Connexion' avec votre email {$candidate->getEmail()} et le mot de passe fourni lors de l'inscription.<br/>";
        $contentMail .= "Merci d'avoir choisi TRT Conseils.<br/><br/>";
        $mail->send($candidate->getEmail(), $candidate->getFirstname() . ' ' . $candidate->getLastname(), 'TRT Conseils - Inscription valid??e', $contentMail);


        return $this->redirectToRoute('app_consultant');
    }

    #[Route('/consultant/recruteur/{id}/approuver', name: 'app_consultant_recruiter_approve')]
    public function approveRecruiter(int $id): Response
    {
        $recruiter = $this->entityManager->getRepository(User::class)->findOneById($id);

        $recruiter->setIs_approved(true);
        $this->entityManager->flush();
        $this->addFlash("success", "La soci??te de recrutement {$recruiter->getCompany()} a bien ??t?? approuv??e.");

        //Envoie d'un mail au recruteur pour le pr??venir de la validation de son compte et donc la possibilit?? de se connecter
        $mail = new Mail();
        $contentMail = "Bonjour,<br/><br/>";
        $contentMail .= "Votre compte a bien ??t?? valid?? par nos ??quipes.<br/>";
        $contentMail .= "Vous pouvez d??sormais vous connecter depuis la rubrique 'Connexion' avec votre email {$recruiter->getEmail()} et le mot de passe fourni lors de l'inscription.<br/>";
        $contentMail .= "Merci d'avoir choisi TRT Conseils.<br/><br/>";
        $mail->send($recruiter->getEmail(), $recruiter->getCompany(), 'TRT Conseils - Inscription valid??e', $contentMail);


        return $this->redirectToRoute('app_consultant');
    }
}
