<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index($reference): Response
    {

        
       
        $YOUR_DOMAIN = 'http://5.196.194.205:8000';
        $product_for_stripe = [];
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        
        // dd($order->getOrderDetails()->getValues());

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

            
            foreach ($order->getOrderDetails()->getValues() as $product) {
                
               if ($product->getMenu() == null) {
                $product_full = $this->entityManager->getRepository(Product::class)->findOneById($product->getIdProduct());
                    $products_for_stripe[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $product->getPrice(),
                            'product_data' => [
                                'name' => $product->getIdProduct(),
                                'images' => [$YOUR_DOMAIN."/uploads".$product_full->getVisual()]
                            ],
                        ],
                        'quantity' => $product->getQuantity(),
                 ];
               }else {
                    if ($product->getMenu()->getId() == '1') {
                        if ($product->getIdProduct()->getCategory()->getId() == '2') {
                            $product_full = $this->entityManager->getRepository(Product::class)->findOneById($product->getIdProduct());
                            $products_for_stripe[] = [
                                'price_data' => [
                                    'currency' => 'eur',
                                    'unit_amount' => $product->getPrice() + 330,
                                    'product_data' => [
                                        'name' => "Menu McKing - ".$product->getIdProduct(),
                                        'images' => [$YOUR_DOMAIN."/uploads".$product_full->getVisual()]
                                    ],
                                ],
                                'quantity' => $product->getQuantity(),
                         ];
                        }
                    }
                    elseif ($product->getMenu()->getId() == '2') {
                        if ($product->getIdProduct()->getCategory()->getId() == '2') {
                            $product_full = $this->entityManager->getRepository(Product::class)->findOneById($product->getIdProduct());
                            $products_for_stripe[] = [
                                'price_data' => [
                                    'currency' => 'eur',
                                    'unit_amount' => $product->getPrice() + 380,
                                    'product_data' => [
                                        'name' => "Menu Maxi McKing - ".$product->getIdProduct(),
                                        'images' => [$YOUR_DOMAIN."/uploads".$product_full->getVisual()]
                                    ],
                                ],
                                'quantity' => $product->getQuantity(),
                         ];
                        }
                    }
                    elseif ($product->getMenu()->getId() == '3') {
                        if ($product->getIdProduct()->getCategory()->getId() == '2') {
                            $product_full = $this->entityManager->getRepository(Product::class)->findOneById($product->getIdProduct());
                            $products_for_stripe[] = [
                                'price_data' => [
                                    'currency' => 'eur',
                                    'unit_amount' => $product->getPrice() + 580,
                                    'product_data' => [
                                        'name' => "Menu McKing Platine - ".$product->getIdProduct(),
                                        'images' => [$YOUR_DOMAIN."/uploads".$product_full->getVisual()]
                                    ],
                                ],
                                'quantity' => $product->getQuantity(),
                         ];
                        }
                    }
                    elseif ($product->getMenu()->getId() == '4') {
                        if ($product->getIdProduct()->getCategory()->getId() == '2') {
                            $product_full = $this->entityManager->getRepository(Product::class)->findOneById($product->getIdProduct());
                            $products_for_stripe[] = [
                                'price_data' => [
                                    'currency' => 'eur',
                                    'unit_amount' => 428,
                                    'product_data' => [
                                        'name' => "Menu McKing Kids - ".$product->getIdProduct(),
                                        'images' => [$YOUR_DOMAIN."/uploads".$product_full->getVisual()]
                                    ],
                                ],
                                'quantity' => $product->getQuantity(),
                         ];
                        }
                    }
                }
                    
                    
               
            }
            


            if ($order->getTotal() != 0) {
            Stripe::setApiKey('sk_test_51MRcwIKaaFB9v3Nj9sgqotmTLQnY9gE2pMOicWV51mfwsKhTpPGXuoozq2o7HxZuQHoCtOpcvfsUFK8KmEiMX5tX008ahHsQ41');

                $checkout_session = Session::create([
                    'customer_email' => $order->getUser()->getEmail(),
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        $products_for_stripe
                    ],
                    'mode' => 'payment',
                    'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
                ]);
            
                
                $order->setStripeSessionId($checkout_session->id);
                $this->entityManager->flush();
                
                

                $response = new JsonResponse(['id' => $checkout_session->id]);
                
                
                return $this->redirect($checkout_session->url);

                } else {

                    return $this->redirectToRoute('order_success_loyalty', array('id' => $order->getId()));

                }
        }
    }

