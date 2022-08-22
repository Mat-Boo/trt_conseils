<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountInfosController extends AbstractController
{
    #[Route('/compte/infos-compte', name: 'app_account_infos')]
    public function index(): Response
    {
        return $this->render('account/infos.html.twig');
    }
}
