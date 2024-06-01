<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AboutController
 * @package App\Controller
 */
class AboutController extends AbstractController
{
    #[Route('/about', name: 'about_page', methods: ['GET'])]
    public function aboutPage(): Response
    {
        return $this->render('about.html.twig');
    }
}