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
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager ,$reference): Response
    {

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $slug => $quantity) {

            $product_full = $entityManager->getRepository(Product::class)->findOneBySlug($slug);

            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product_full->getPrice(),
                    'product_data' => [
                        'name' => $product_full->getIdProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads".$product_full->getVisual()]
                    ],
                ],
                'quantity' => $quantity,
            ];
        }

        Stripe::setApiKey('sk_test_51MWxSqJBf835V8Oop84OnNLXAEpwVaPYgqugvJhX0q88Fin7nJAXGpUCSlTcqwb6lXLYJlRTmBP8WDnmv8FmBFtq00x7AnkH07');

            $checkout_session = Session::create([

                'payment_method_types' => ['card'],
                'line_items' => [
                    $products_for_stripe
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);

            // dump($checkout_session->id);
            // dd($checkout_session);

            $response = new JsonResponse(['id' => $checkout_session->id]);

            return $response;
        }
    }

