<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Postal;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FactureController extends AbstractController
{
    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }
    #[Route('/facture/{stripeSessionId}', name: 'facture')]
    public function index($stripeSessionId)
    {
        
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = $this->entityManager->getRepository(User::class)->findAll();
        $city = $this->entityManager->getRepository(Postal::class)->findAll();
        $order_details = $this->entityManager->getRepository(OrderDetails::class)->findAll();
        $product = $this->entityManager->getRepository(Product::class)->findAll();
        $order_history = [];
        foreach ($order_details as $value) {
           if(($value->getIdOrder()->getId()) == $order->getId()){
            $order_history[] = $value;
           }
        }
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Lilita One');
        $pdfOptions->setIsRemoteEnabled(true);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('order_success/facture.html.twig', [
            'order' => $order,
            'orderDetails' => $order_history
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture".$order->getReference().".pdf", [
            "Attachment" => true
        ]);
    }
    #[Route('/facture/{id}', name: 'facture_id')]
    public function show($id)
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneById($id);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = $this->entityManager->getRepository(User::class)->findAll();
        $city = $this->entityManager->getRepository(Postal::class)->findAll();
        $order_details = $this->entityManager->getRepository(OrderDetails::class)->findAll();
        $product = $this->entityManager->getRepository(Product::class)->findAll();
        $order_history = [];
        foreach ($order_details as $value) {
           if(($value->getIdOrder()->getId()) == $order->getId()){
            $order_history[] = $value;
           }
        }
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Lilita One');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('order_success/facture.html.twig', [
            'order' => $order,
            'orderDetails' => $order_history
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture".$order->getReference().".pdf", [
            "Attachment" => true
        ]);
    }
}
