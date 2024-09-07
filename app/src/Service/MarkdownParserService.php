<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

/**
 * Class MarkdownParserService
 * @package App\Service
 */
class MarkdownParserService
{
    /**
     * Parse the Markdown content
     *
     * @param string $content
     * @return array
     */
    public function parse(string $content): array
    {
        if (preg_match('/^---(.*?)---\s*(.*)$/s', $content, $matches)) {
            $metadata = Yaml::parse($matches[1]);
            $markdown = $matches[2];
        } else {
            $metadata = [];
            $markdown = $content;
        }

        return [$metadata, $markdown];
    }
}