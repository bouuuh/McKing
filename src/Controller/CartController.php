<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Menu;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {

        // dd($cart->get());

        $cartCompleteProduct = [];

        foreach ($cart->get() as $slug => $quantity) {
            $cartCompleteProduct[] = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneBySlug($slug),
                'menu' => $this->entityManager->getRepository(Menu::class)->findOneBySlug($slug),
                'quantity' => $quantity
            ];
        }
        //$cartCompleteMenu = [];

        // foreach ($cart->get() as $slug => $quantity) {
        //     $cartCompleteProduct[] = [
        //         'menu' => $this->entityManager->getRepository(Menu::class)->findOneBySlug($slug),
        //         'quantity' => $quantity
        //     ];
        // }
        

        // dd($cartComplete);

        return $this->render('cart/index.html.twig', [
            'cart' => $cartCompleteProduct,
            //'cartMenu' => $cartCompleteMenu
        ]);
    }
    #[Route('/cart/add/{slug}', name: 'add_to_cart')]
    public function add(Cart $cart, $slug): Response
    {
        $cart->add($slug);

        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/add/{slug}', name: 'add_menu_to_cart')]
    public function addMenu(Cart $cart, $slug): Response
    {
        $cart->addMenu($slug);

        return $this->redirectToRoute('cart');
    }
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
}
