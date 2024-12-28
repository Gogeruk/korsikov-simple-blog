<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BusinessCardController
 * @package App\Controller
 */
class BusinessCardController extends AbstractController
{
    #[Route('/business-card', name: 'business_card', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('business_card.html.twig');
    }
}