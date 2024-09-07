<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ExceptionListener
 * @package App\EventListener
 */
readonly class ExceptionListener
{
    /**
     * @param Environment $twig
     */
    public function __construct(private Environment $twig) {}

    /**
     * @param ExceptionEvent $event
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        // when page not found
        if ($statusCode === Response::HTTP_NOT_FOUND) {
            $response = new Response(
                $this->twig->render('errors/not_found.html.twig'),
                Response::HTTP_NOT_FOUND
            );

            $event->setResponse($response);
            return;
        }

        // when there's an error
        $response = new Response(
            $this->twig->render('errors/error_page.html.twig', ['exception' => $exception]),
            $statusCode
        );

        $event->setResponse($response);
    }
}