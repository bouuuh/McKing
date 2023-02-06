<?php

namespace App\Controller;

use App\Entity\IllustrationHome;
use App\Entity\Loyalty;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {

        $illustration_home = $this->entityManager->getRepository(IllustrationHome::class)->findAll();

        $pointsSession = null;
        
if ($this->getUser()) {
    $pointsUser = $this->getUser()->getPoints();
        $pointsSession = $this->session->get('pointsUserSession', []);
    if (empty($this->session->get('points', []))) {
        $this->session->set('pointsUserSession', $pointsUser);
        } else {
            $this->session->set('pointsUserSession', $pointsUser);
            $points = $this->session->get('points', []);

                foreach ($points as $value) {
                    $number_points_name = $this->entityManager->getRepository(Product::class)->findOneById($value->getId());
                    $number_points = $this->entityManager->getRepository(Loyalty::class)->findOneByProduct($number_points_name);
                        if($number_points->getNumber_points() == 0){
                            $pointsSession = $this->session->get('pointsUserSession', []);
                        $pointsSession = $pointsSession - 15;
                        
                        }
                        elseif ($number_points->getNumber_points() == 1) {
                            $pointsSession = $this->session->get('pointsUserSession', []);
                            $pointsSession = $pointsSession - 30;
                        }
                        elseif ($number_points->getNumber_points() == 2) {
                            $pointsSession = $this->session->get('pointsUserSession', []);
                            $pointsSession = $pointsSession - 60;
                        }
                         elseif ($number_points->getNumber_points() == 3) {
                            $pointsSession = $this->session->get('pointsUserSession', []);
                             $pointsSession = $pointsSession - 90;
                         }
                         $this->session->set('pointsUserSession', $pointsSession);
                }
                
            
        }
}


        return $this->render('home/index.html.twig', [
            'illustration' => $illustration_home,
            'points_session' => $pointsSession
        ]);
    }
}
