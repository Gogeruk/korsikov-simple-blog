<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperSecretController extends AbstractController
{
    #[Route('/schizophrenia-one', name: 'schizophrenia_one_page', methods: ['GET'])]
    public function schizophreniaOnePage(): Response
    {
        return $this->render('schizophrenia_one.html.twig');
    }
}