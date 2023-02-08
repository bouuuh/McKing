<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Menu;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }


    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart, Request $request): Response
    {

        

        $cartCompleteProduct = [];
        if ($this->session->get('cart', []) != null) { 
            foreach ($cart->get() as $slug => $quantity) {
                $cartCompleteProduct[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneBySlug($slug),
                    'quantity' => $quantity
                ];
            }
       }

        
       

        $menus = $this->session->get('list_item', []);

        $points = $this->session->get('points', []);
       
        

        return $this->render('cart/index.html.twig', [
            'cart' => $cartCompleteProduct,
            'menus' => $menus,
            'points' => $points
        ]);
    }
    #[Route('/cart/add/{slug}', name: 'add_to_cart')]
    public function add(Cart $cart, $slug): Response
    {
        $cart->add($slug);

        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/points/{slug}', name: 'add_points_to_cart')]
    public function addPoints($slug): Response
    {

        $points = $this->session->get('points', []);

        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        if (!empty($points[$slug])) {
            $points[$slug]= $product;
        } else {
            $points[$slug] = $product;
        }

        $this->session->set('points', $points);


        return $this->redirectToRoute('cart');
    }
    // #[Route('/cart/add/{id}', name: 'add_menu_to_cart')]
    // public function addMenu(Cart $cart, $id): Response
    // {
    //     $cart->addMenu($id);

    //     return $this->redirectToRoute('cart');
    // }
    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('category');
    }

    #[Route('/cart/delete/{slug}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $slug): Response
    {
        
        $cart->delete($slug);

        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/delete-menu/{id}', name: 'delete_to_cart_menu')]
    public function deleteMenu($id): Response
    {

        $list_item = $this->session->get('list_item', []);

        unset($list_item[$id]);

        $this->session->set('list_item', $list_item);
        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/delete-points/{slug}', name: 'delete_to_cart_points')]
    public function deletePoints($slug): Response
    {

        $points = $this->session->get('points', []);
        unset($points[$slug]);

        $this->session->set('points', $points);
        return $this->redirectToRoute('cart');
    }
}
