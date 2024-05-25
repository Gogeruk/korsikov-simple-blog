<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_page', methods: ['GET'])]
    public function homePage(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/contact', name: 'contact_page', methods: ['GET'])]
    public function contactPage(): Response
    {
        return $this->render('contact.html.twig');
    }
}
