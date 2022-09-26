<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReceiptController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/receipt/{id}", name="app_receipt")
     */
    public function index($id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneById($id);
        $orderDetails = $this->entityManager->getRepository(OrderDetails::class)->findOrderId($id);

        // Configure Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instancie Dompdf avec les options
        $dompdf = new Dompdf($pdfOptions);

        $dompdf->getOptions()->setChroot('assets/css/pdf.css');

        // Retrouve la vue HTML pour ma facture
        $html = $this->renderView('/account/receipt.html.twig', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $dompdf->stream();

        // // Store PDF Binary Data
        $output = $dompdf->output();

        // // Send some text response
        return $this->render('account/receipt.html.twig', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'html' => $html,
        ]);
    }
}
