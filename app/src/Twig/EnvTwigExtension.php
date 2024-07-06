<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class EnvTwigExtension
 * @package App\Twig
 */
class EnvTwigExtension extends AbstractExtension
{
    /**
     * @param ParameterBagInterface $params
     */
    public function __construct(readonly ParameterBagInterface $params) {}

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getEnv', [$this, 'getEnv']),
        ];
    }

    /**
     * @param string $envName
     * @return string|null
     */
    public function getEnv(string $envName): ?string
    {
        return $this->params->get($envName);
    }
}