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
    #[Route('/', name: 'home_page', methods: ['GET'])]
    public function homePage(): Response
    {
        return $this->render('home.html.twig', [
            'random' => str_pad((string)mt_rand(1, 135), 3, '0', STR_PAD_LEFT),
        ]);
    }
}