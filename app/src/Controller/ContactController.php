<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_page', methods: ['GET'])]
    public function contactPage(): Response
    {
        return $this->render('home.html.twig');
    }
}