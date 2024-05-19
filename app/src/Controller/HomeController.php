<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 */
class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_page', methods: ['GET'])]
    public function homePage(Request $request): Response
    {
        return $this->render('home.html.twig');
    }
}
