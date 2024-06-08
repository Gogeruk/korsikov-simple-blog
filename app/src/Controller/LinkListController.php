<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LinkListController
 * @package App\Controller
 */
class LinkListController extends AbstractController
{
    #[Route('/link-list', name: 'link_list_page', methods: ['GET'])]
    public function homePage(): Response
    {
        return $this->render('link-list.html.twig');
    }
}