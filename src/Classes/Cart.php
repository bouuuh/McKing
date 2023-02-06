<?php

namespace App\Classes;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($slug)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$slug])) {
            $cart[$slug]++;
        } else {
            $cart[$slug] = 1;
        }

        $this->session->set('cart', $cart);

    }
    public function addMenu($slug)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$slug])) {
            $cart[$slug]++;
        } else {
            $cart[$slug] = 1;
        }

        $this->session->set('cart', $cart);

    }
    public function get()
    {
        return $this->session->get('cart');

    }
    public function remove()
    {
        return $this->session->remove('cart');

    }
    public function delete($slug)
        {
            $cart = $this->session->get('cart', []);

            unset($cart[$slug]);

            return $this->session->set('cart', $cart);
        }

public function getFull()
        {
            
            $cartCompleteProduct = [];


            if ($this->get()){

                foreach ($this->get() as $id => $quantity) {

                    $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                    if (!$product_object) {
                        $this->delete($id);
                        continue;
                    }

                    $cartCompleteProduct[] = [
                        'product' => $product_object,
                        'quantity' => $quantity
                    ];
                }
            }

            return $cartCompleteProduct;
        }
}

