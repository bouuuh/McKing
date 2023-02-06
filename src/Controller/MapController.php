<?php

namespace App\Controller;

use App\Entity\Postal;
use App\Form\PostalType;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }
    #[Route('/code-postal', name: 'map')]
    public function index(Request $request): Response
    {

        $postals = $this->entityManager->getRepository(Postal::class)->findAll();
        $form = $this->createForm(PostalType::class);
        $form->handleRequest($request);



        return $this->render('map/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
