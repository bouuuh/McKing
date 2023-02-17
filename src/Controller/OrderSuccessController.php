<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Classes\Cart;
use App\Entity\Loyalty;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index($stripeSessionId, Cart $cart, ): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        // dd($order);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        
        

        if ($order->getState() == 0) {
                $user = $this->getUser();
                $points = $user->getPoints();
                $total = $order->getTotal();
                $total = $total/100;
                $points = ($points + $total);
                $user->setPoints($points);

            $points = $this->session->get('points', []);
             if($points != null){
             foreach ($this->session->get('points', []) as $slug => $quantity) {
                $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
                $user = $this->getUser();
                $points = $user->getPoints();
                $points_object = $this->entityManager->getRepository(Loyalty::class)->findOneByProduct($product_full);
                if($points_object->getNumberPoints() == 0){
                    $test = 15;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
                elseif($points_object->getNumberPoints() == 1){
                    $test = 30;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
                elseif($points_object->getNumberPoints() == 2){
                    $test = 60;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
                elseif($points_object->getNumberPoints() == 3){
                    $test = 90;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
            }
                $user->setPoints(round($points));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
        }





            $cart->remove();
            $this->session->set('list_item', null);
            $this->session->set('points', null); 

            $order->setState(1);
            $this->entityManager->flush();
            $userAdmin = $this->entityManager->getRepository(User::class)->findAll();





            $mail = new Mail();
            $content = "Bonjour ".$order->getuser()->getFirstName()."<br> Merci pour votre commande.<br> ";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Votre commande McKing est bien validée', 'Commande chez McKing', $content);
            
            $adminEmail = [];

            foreach ($userAdmin as $value) {
                foreach ($value->getRoles() as $role) {
                   if ($role == "ROLE_ADMIN") {
                    $adminEmail[] = [$value->getEmail()];
                } 
                }
            }
            foreach ($adminEmail as $value) {
                foreach ($value as $email) {
                    $mailAdmin = new Mail();
                    $contentAdmin = "La commande ".$order->getReference()." a été validée, veuillez la préparer";
                    $mailAdmin->send($email, $order->getUser()->getFirstName(), 'Votre commande McKing est bien validée', 'Commande chez McKing', $contentAdmin);
                }
                
            }
            
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/commande/valider/{id}', name: 'order_success_loyalty')]
    public function show($id): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneById($id);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getState() == 0) {

            $points = $this->session->get('points', []);
             if($points != null){
             foreach ($this->session->get('points', []) as $slug => $quantity) {
                $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
                $user = $this->getUser();
                $points = $user->getPoints();
                $points_object = $this->entityManager->getRepository(Loyalty::class)->findOneByProduct($product_full);
                if($points_object->getNumberPoints() == 0){
                    $test = 15;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
                elseif($points_object->getNumberPoints() == 1){
                    $test = 30;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
                elseif($points_object->getNumberPoints() == 2){
                    $test = 60;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
                elseif($points_object->getNumberPoints() == 3){
                    $test = 90;
                    $points = ($points - $test);
                    $user->setPoints($points);
                }
            }
                $user->setPoints(round($points));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
        }


            $this->session->set('points', null);

            $order->setState(1);
            $this->entityManager->flush();

            $mail = new Mail();
            $content = "Bonjour ".$order->getuser()->getFirstName()."<br> Merci pour votre commande.<br>";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Votre commande McKing est bien validée', 'Commande chez McKing', $content);
            
            $userAdmin = $this->entityManager->getRepository(User::class)->findAll();
            $adminEmail = [];

            foreach ($userAdmin as $value) {
                foreach ($value->getRoles() as $role) {
                   if ($role == "ROLE_ADMIN") {
                    $adminEmail[] = [$value->getEmail()];
                } 
                }
            }
            foreach ($adminEmail as $value) {
                foreach ($value as $email) {
                    $mailAdmin = new Mail();
                    $contentAdmin = "La commande ".$order->getReference()." a été validée, veuillez la préparer";
                    $mailAdmin->send($email, $order->getUser()->getFirstName(), 'Votre commande McKing est bien validée', 'Commande chez McKing', $contentAdmin);
                }
        }
    }
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
    
}
