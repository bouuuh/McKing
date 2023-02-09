<?php

namespace App\Controller;

use App\Entity\Postal;
use App\Form\PostalType;
use App\Form\RegisterFormType;
use App\Repository\PostalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $city_session = $this->session->get('city_session', []);
        $postals = $this->entityManager->getRepository(Postal::class)->findAll();
        $form = $this->createForm(PostalType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id_city = $form->get('code')->getData();
            $chosen_city = $this->entityManager->getRepository(Postal::class)->findOneById($id_city);
            $city_session = $this->session->get('city_session', []);
            if (!empty($city_session)) {
                $city_session = $chosen_city;
            } else {
                $city_session = $chosen_city;
            }
            $this->session->set('city_session', $chosen_city);
        }



        return $this->render('map/index.html.twig', [
            'form' => $form->createView(),
            'city' => $city_session
        ]);
    }
    #[Route('/_cities', name: 'cities')]
    public function show(PostalRepository $postalRepository, Request $request, SerializerInterface $serializer): Response
    {
        
        $searched_term = $request->query->get('q');
        $cities = $postalRepository->findByCityOrCode($searched_term);

        $jsonContent = $serializer->serialize($cities, 'json');
        return $this->json($jsonContent);

    }
}