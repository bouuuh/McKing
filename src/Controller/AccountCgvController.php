<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountCgvController extends AbstractController
{
    #[Route('/mon-compte/cgv', name: 'account_cgv')]
    public function index(): Response
    {
        return $this->render('account/cgv.html.twig');
    }
}
