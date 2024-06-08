<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MarkdownParserService;
use App\Service\ReallySimpleSyndicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Parsedown;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_page', methods: ['GET'])]
    public function blogPage(Request $request, MarkdownParserService $markdownParser): Response
    {
        // get tags from query string
        $tag = $request->query->get('tag');

        // get all markdown files
        $markdownDirectory = $this->getParameter('kernel.project_dir') . '/markdown';
        $finder = new Finder();
        $finder->files()->in($markdownDirectory)->name('*.md');

        // parse the markdown files
        $posts = [];
        $allTags = [];
        foreach ($finder as /** @var SplFileInfo $file */ $file) {
            $content = file_get_contents($file->getRealPath());
            $metadata = $markdownParser->parse($content)[0] ?? [];

            if (isset($metadata['tags'])) {
                $allTags = array_merge($allTags, $metadata['tags']);
            }

            // when tag is set and the post does not have the tag
            if ($tag && (!isset($metadata['tags']) || !in_array($tag, $metadata['tags']))) {
                continue;
            }

            $slug = $file->getBasename('.md');
            $posts[] = [
                'slug'  => $slug,
                'title' => $metadata['title'] ?? ucfirst($slug),
                'tags'  => $metadata['tags'] ?? [],
            ];
        }

        // remove duplicate tags
        $allTags = array_unique($allTags);

        return $this->render('blog/blog.html.twig', [
            'posts'       => $posts,
            'all_tags'    => $allTags,
            'current_tag' => $tag,
            'title'       => null,
            'content'     => null,
            'tags'        => [],
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_post', methods: ['GET'])]
    public function blogPost(string $slug, MarkdownParserService $markdownParser): Response
    {
        // get the markdown file
        $markdownDirectory = $this->getParameter('kernel.project_dir') . '/markdown';
        $filePath = $markdownDirectory . '/' . $slug . '.md';

        // when the file does not exist
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The blog post does not exist');
        }

        // parse the markdown file
        $content = file_get_contents($filePath);
        [$metadata, $markdown] = $markdownParser->parse($content);
        $parsedown = new Parsedown();
        $htmlContent = $parsedown->text($markdown);

        return $this->render('blog/blog.html.twig', [
            'content'     => $htmlContent,
            'title'       => $metadata['title'] ?? ucfirst($slug),
            'tags'        => $metadata['tags'] ?? [],
            'posts'       => null,
            'all_tags'    => null,
            'current_tag' => null
        ]);
    }

    #[Route('/api/v1/blog', name: 'api_v1_blog_list', methods: ['GET'])]
    public function apiBlogList(Request $request, MarkdownParserService $markdownParser): JsonResponse
    {
        // get tags from query string
        $tag = $request->query->get('tag');

        // get all markdown files
        $markdownDirectory = $this->getParameter('kernel.project_dir') . '/markdown';
        $finder = new Finder();
        $finder->files()->in($markdownDirectory)->name('*.md');

        // parse the markdown files
        $posts = [];
        foreach ($finder as /** @var SplFileInfo $file */ $file) {
            $content = file_get_contents($file->getRealPath());
            $metadata = $markdownParser->parse($content)[0] ?? [];

            // when tag is set and the post does not have the tag
            if ($tag && (!isset($metadata['tags']) || !in_array($tag, $metadata['tags']))) {
                continue;
            }

            $slug = $file->getBasename('.md');
            $posts[] = [
                'slug'  => $slug,
                'title' => $metadata['title'] ?? ucfirst($slug),
                'tags'  => $metadata['tags'] ?? [],
            ];
        }

        return new JsonResponse($posts);
    }

    #[Route('/rss-blog', name: 'rss_blog_feed', methods: ['GET'])]
    public function rssBlogFeed(MarkdownParserService $markdownParser, ReallySimpleSyndicationService $rssService): Response
    {
        // get all markdown files
        $markdownDirectory = $this->getParameter('kernel.project_dir') . '/markdown';
        $finder = new Finder();
        $finder->files()->in($markdownDirectory)->name('*.md');

        // parse the markdown files
        $posts = [];
        foreach ($finder as /** @var SplFileInfo $file */ $file) {
            $content = file_get_contents($file->getRealPath());
            $metadata = $markdownParser->parse($content)[0] ?? [];

            $slug = $file->getBasename('.md');
            $posts[] = [
                'slug'        => $slug,
                'title'       => $metadata['title'] ?? ucfirst($slug),
                'date'        => $metadata['date'] ?? date('Y-m-d'),
                'link'        => $this->generateUrl('blog_post', ['slug' => $slug], UrlGeneratorInterface::ABSOLUTE_URL),
                'description' => $metadata['description'] ?? '',
            ];
        }

        // generate RSS feed content
        $rssFeed = $rssService->generateFeed(
            $posts,
            $this->generateUrl('blog_page', [], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        // return RSS feed response
        return new Response($rssFeed, 200, ['Content-Type' => 'application/rss+xml']);
    }
}