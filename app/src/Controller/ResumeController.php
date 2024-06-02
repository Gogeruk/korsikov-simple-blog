<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResumeController
 * @package App\Controller
 */
class ResumeController extends AbstractController
{
    #[Route('/cv', name: 'cv_page', methods: ['GET'])]
    public function cvPage(): Response
    {
        return $this->render('cv.html.twig');
    }
}