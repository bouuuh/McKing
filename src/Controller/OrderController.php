<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Loyalty;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Postal;
use App\Entity\Product;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    #[Route('/commande', name: 'order')]
    public function index(Request $request, Cart $cart): Response
    {

        if (!($this->getUser())) {
            return $this->redirectToRoute('login');
        }

        
        $cartCompleteProduct = [];
        if ($this->session->get('cart', []) != null) { 
            foreach ($cart->get() as $slug => $quantity) {
                $cartCompleteProduct[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneBySlug($slug),
                    'quantity' => $quantity
                ];
            }
       }
       $points = $this->session->get('points', []);
        return $this->render('order/index.html.twig', [
            'cart' => $cartCompleteProduct,
            'menu' => $this->session->get('list_item', []),
            'points' => $points
        ]);
    }
    #[Route('/commande/final', name: 'order_final')]
    public function add(Request $request, Cart $cart): Response
    {

        if (!($this->getUser())) {
            return $this->redirectToRoute('login');
        }
        $city = $this->session->get('city_session', []);
        

        if ($city == null) {
            return $this->redirectToRoute('map');
        }

        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        $date = new \DateTime;
        $reference = $date->format('dmY').'-'.uniqid();
        if ($form->isSubmitted() && $form->isValid()) {
            
            $place = $form->get('place')->getData();
            
            $order = new Order();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setDate($date);
            $order->setPlace($place);
            $order->setState(0);
            $city_full = $this->entityManager->getRepository(Postal::class)->findOneById($city->getId());
            $order->setCity($city_full);

            $this->entityManager->persist($order);

            $total = 0;
            
            if ($this->session->get('cart', []) != null) {
            foreach ($this->session->get('cart', []) as $slug => $quantity) {

                $orderDetails = new OrderDetails();
                $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
                $orderDetails->setIdOrder($order);
                $orderDetails->setIdProduct($product_full);
                $orderDetails->setQuantity($quantity);
                $orderDetails->setPrice($product_full->getPrice());
                $orderDetails->setTotal($product_full->getPrice() * $quantity);
                $user = $this->getUser();
                $points = $user->getPoints();
                $test = ($product_full->getPrice() * $quantity)/100;
                $points = ($points + $test);
                $user->setPoints($points);
                $this->entityManager->persist($orderDetails);
                $total = $total + ($product_full->getPrice() * $quantity);
                
            }
            if(!empty($this->session->get('cart', []))){
                $user->setPoints(number_format($points, 0));
                $this->entityManager->persist($user);
            }
        }
            $menus = $this->session->get('list_item', []);
            
            if ($menus != null) {
            
            foreach ($menus as $value) {              
                  if ($value['menu']->getId() != '4') {

                    foreach ($value as $key => $values) {
                        if ($key == 'burger' || $key == 'drink' || $key == 'sauce') {
                        $orderDetails = new OrderDetails();
                        $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($values->getSlug());
                        $orderDetails->setIdOrder($order);
                        $orderDetails->setIdProduct($product_full);
                        $orderDetails->setIdMenu($value['menuId']);
                        $menu_full = $this->entityManager->getRepository(Menu::class)->findOneById($value['menu']->getId());
                        $orderDetails->setMenu($menu_full);
                        $orderDetails->setQuantity(1);
                        $orderDetails->setPrice($product_full->getPrice());
                        $orderDetails->setTotal($product_full->getPrice());
                        $user = $this->getUser();
                        $points = $user->getPoints();
                        $test = ($product_full->getPrice())/100;
                        $points = ($points + $test);
                        $user->setPoints($points);
                        $this->entityManager->persist($orderDetails);
                        $total = $total + ($product_full->getPrice());
                        }
                        if($key == 'snack' && $values != null){
                            $orderDetails = new OrderDetails();
                            $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($values->getSlug());
                            $orderDetails->setIdOrder($order);
                            $orderDetails->setIdProduct($product_full);
                            $orderDetails->setIdMenu($value['menuId']);
                        $menu_full = $this->entityManager->getRepository(Menu::class)->findOneById($value['menu']->getId());
                        $orderDetails->setMenu($menu_full);
                            $orderDetails->setQuantity(1);
                            $orderDetails->setPrice(200);
                            $orderDetails->setTotal(200);
                            $user = $this->getUser();
                            $points = $user->getPoints();
                            $test = 2;
                            $points = ($points + $test);
                            $user->setPoints($points);
                            $total = $total + ($product_full->getPrice());
                            $this->entityManager->persist($orderDetails);
                        }

                    }
                    if(!empty($this->session->get('list-item', []))){
                        $user->setPoints(number_format($points, 0));
                        $this->entityManager->persist($user);
                    }
                   
               }
                if ($value['menu']->getId() == '4') {
                    foreach ($value as $key => $values) {
                        if ($key == 'burger' || $key == 'drink' || $key == 'sauce' || $key == 'dessert') {
                            $orderDetails = new OrderDetails();
                            $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($values->getSlug());
                            $orderDetails->setIdOrder($order);
                            $orderDetails->setIdProduct($product_full);
                            $orderDetails->setIdMenu($value['menuId']);
                            $menu_full = $this->entityManager->getRepository(Menu::class)->findOneById($value['menu']->getId());
                            $orderDetails->setMenu($menu_full);
                            $orderDetails->setQuantity(1);
                            $orderDetails->setPrice(107.5);
                            $orderDetails->setTotal(107.5);
                            $user = $this->getUser();
                            $points = $user->getPoints();
                            $test = 1.075;
                            $points = ($points + $test);
                            $user->setPoints($points);
                            $total = $total + ($product_full->getPrice());
                            $this->entityManager->persist($orderDetails);
                            }
                    }
                    if(!empty($this->session->get('list-item', []))){
                        $user->setPoints(number_format($points, 0));
                        $this->entityManager->persist($user);
                    }
                }

             }
             }   
             $points = $this->session->get('points', []);

             if($points != null){

             foreach ($this->session->get('points', []) as $slug => $quantity) {
                
                $orderDetails = new OrderDetails();
                $product_full = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
                $orderDetails->setIdOrder($order);
                $orderDetails->setIdProduct($product_full);
                $orderDetails->setQuantity(1);
                $orderDetails->setPrice(0);
                $orderDetails->setTotal(0);
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
                
                 $this->entityManager->persist($orderDetails);
                
            }
            if(!empty($this->session->get('points', []))){
                $user->setPoints(round($points));
                $this->entityManager->persist($user);
            }
        }
            $order->setTotal($total);
        $this->entityManager->flush();
        return $this->redirectToRoute('stripe_create_session', array('reference' => $reference));
        }


        
        

        return $this->render('order/add.html.twig', [
            'form' => $form->createView(),
            'reference' => $reference,
            'menu' => $this->session->get('list_item', []),
            
        ]);
    }
}
