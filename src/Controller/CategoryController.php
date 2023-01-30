<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Product;
use App\Form\MenuFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/commander/{category}/{slug}', name: 'product')]
    public function show_product($slug): Response
    {
      
            $product_slug = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
            $category = $product_slug->getCategory();
            $id = $category->getId();
            $category = $this->entityManager->getRepository(Category::class)->findOneById($id);
            
            if (!$product_slug) {
                return $this->redirectToRoute('category');
            }

           
            
   
        return $this->render('category/product.html.twig', [
            'slug' => $slug,
            'product' => $product_slug,
            'category' => $category,
        ]);
    }

    #[Route('/menus/{slug}', name: 'menu')]
    public function show_menu($slug, Request $request): Response
    {
      
        
        $menu_slug = $this->entityManager->getRepository(Menu::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findAll();
            
        if (!$menu_slug) {
            return $this->redirectToRoute('category');
        }


        $form = $this->createForm(MenuFormType::class, [
            'burger' => $this->entityManager->getRepository(Product::class)->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $burger = $form->get('burger')->getData();
            $snack = $form->get('snack')->getData();
            $drink = $form->get('drink')->getData();
            $sauce = $form->get('sauce')->getData();
            $dessert = $form->get('dessert')->getData();
        }

   
        return $this->render('category/menu.html.twig', [
            'slug' => $slug,
            'menus' => $menu_slug,
            'products' => $products,
            'form' => $form->createView(),
        ]); 
    }
}
