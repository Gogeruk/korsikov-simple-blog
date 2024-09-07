<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class BlogService
 * @package App\Service
 */
class BlogService
{
    /**
     * @var string
     */
    private string $markdownDirectory;

    /**
     * @param string $projectDir
     * @param MarkdownParserService $markdownParser
     */
    public function __construct(
        readonly string $projectDir,
        private readonly MarkdownParserService $markdownParser
    )
    {
        $this->markdownDirectory = $projectDir . '/markdown';
    }

    /**
     * Get all Markdown files
     *
     * @return Finder
     */
    public function getAllMarkdownFiles(): Finder
    {
        $finder = new Finder();
        return $finder->files()->in($this->markdownDirectory . '/blog')->name('*.md');
    }

    /**
     * Parse the Markdown file
     *
     * @param SplFileInfo $file
     * @return array
     */
    public function parseMarkdownFile(SplFileInfo $file): array
    {
        return $this->markdownParser->parse(file_get_contents($file->getRealPath()));
    }

    /**
     * Get blog posts
     *
     * @param string|null $tag
     * @return array
     */
    #[ArrayShape(['posts' => "array", 'allTags' => "array"])]
    public function getBlogPosts(?string $tag = null): array
    {
        $finder = $this->getAllMarkdownFiles();
        $posts = [];
        $allTags = [];

        foreach ($finder as $file) {
            [$metadata, ] = $this->parseMarkdownFile($file);
            $metadata = $metadata ?? [];

            if (isset($metadata['tags'])) {
                $allTags = array_merge($allTags, $metadata['tags']);
            }

            if ($tag && (!isset($metadata['tags']) || !in_array($tag, $metadata['tags']))) {
                continue;
            }

            $slug = $file->getBasename('.md');
            $posts[] = [
                'slug'        => $slug,
                'title'       => $metadata['title'] ?? ucfirst($slug),
                'tags'        => $metadata['tags'] ?? [],
                'date'        => $metadata['date'] ?? null,
                'description' => $metadata['description'] ?? ''
            ];
        }

        return ['posts' => $posts, 'allTags' => array_unique($allTags)];
    }

    /**
     * Get a blog post
     *
     * @param string $slug
     * @return array
     * @throws Exception
     */
    public function getBlogPost(string $slug): array
    {
        $filePath = $this->markdownDirectory . '/blog/' . $slug . '.md';

        if (!file_exists($filePath)) {
            throw new Exception('The blog post does not exist');
        }

        $content = file_get_contents($filePath);
        return $this->markdownParser->parse($content);
    }

    /**
     * Get links from Markdown file
     *
     * @return array
     * @throws Exception
     */
    public function getLinks(): array
    {
        $filePath = $this->markdownDirectory . '/links/link_list.md';

        if (!file_exists($filePath)) {
            throw new Exception('The link list file does not exist');
        }

        $content = file_get_contents($filePath);
        return $this->parseLinks($content);
    }

    /**
     * Parse links from Markdown content
     *
     * @param string $content
     * @return array
     */
    private function parseLinks(string $content): array
    {
        $lines = explode("\n", $content);
        $links = [];

        foreach ($lines as $line) {
            if (preg_match('/- \[(.*?)]\((.*?)\) - (.+)/', $line, $matches)) {
                $links[] = [
                    'title'       => $matches[1] ?? '',
                    'url'         => $matches[2] ?? '',
                    'description' => $matches[3] ?? ''
                ];
            }
        }

        return $links;
    }
}