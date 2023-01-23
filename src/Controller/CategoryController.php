<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/commander', name: 'category')]
    public function index(): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->findAll();



        return $this->render('category/index.html.twig', [
            'category' => $category
        ]);
    }
    #[Route('/commander/{slug}', name: 'category_slug')]
    public function show($slug): Response
    {
        $menus = null;
        $products = null;

        $category_slug = $this->entityManager->getRepository(Category::class)->findOneBySlug($slug);

        if (!$category_slug) {
            return $this->redirectToRoute('category');
        }
        

        if ($category_slug->getSlug() === 'menus') {
            $menus = $this->entityManager->getRepository(Menu::class)->findAll();
        } else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }



        return $this->render('category/show.html.twig', [
            'category_slug' => $category_slug,
            'menus' => $menus,
            'products' => $products
        ]);
    }

    #[Route('/commander/burgers/{slug}', name: 'product_slug')]
    public function showProduct($slug): Response 
    {

        $product_slug = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        

        return $this->render('category/product.html.twig', [
            'product_slug' => $product_slug
        ]);
    }

}
