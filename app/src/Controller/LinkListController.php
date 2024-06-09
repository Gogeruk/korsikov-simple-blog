<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use Exception;
use RuntimeException;
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
    public function homePage(BlogService $blogService): Response
    {
        try {
            $links = $blogService->getLinks();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        shuffle($links);

        return $this->render('link-list.html.twig', [
            'links' => $links,
        ]);
    }
}