<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PrivacyPolicyController
 * @package App\Controller
 */
class PrivacyPolicyController extends AbstractController
{
    #[Route('/privacy-policy', name: 'privacy_policy_page', methods: ['GET'])]
    public function privacyPolicyPage(): Response
    {
        return $this->render('privacy-policy.html.twig');
    }
}