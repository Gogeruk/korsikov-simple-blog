<?php

declare(strict_types=1);

namespace App\EventListener;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * Class RateLimiterListener
 * @package App\EventListener
 */
class RateLimiterListener implements EventSubscriberInterface
{
    /**
     * @param RateLimiterFactory $rateLimiter
     */
    public function __construct(private readonly RateLimiterFactory $rateLimiter) {}

    /**
     * @return string[]
     */
    #[ArrayShape(['kernel.request' => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }

    /**
     * See rate_limiter.yaml for configuration
     *
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // create rate limiter for the given identifier (client IP)
        $limiter = $this->rateLimiter->create($event->getRequest()->getClientIp());

        // consume the rate limiter
        $consume = $limiter->consume();

        // check if request is accepted
        if (!$consume->isAccepted()) {
            $response = new JsonResponse(['message' => 'Rate limit exceeded!']);
            $event->setResponse($response);
        }
    }
}