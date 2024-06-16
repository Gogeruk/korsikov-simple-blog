<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BlogService;
use App\Service\ReallySimpleSyndicationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Parsedown;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_page', methods: ['GET'])]
    public function blogPage(Request $request, BlogService $blogService): Response
    {
        $tag = $request->query->get('tag');
        $result = $blogService->getBlogPosts($tag);

        return $this->render('blog/blog.html.twig', [
            'posts'       => $result['posts'],
            'all_tags'    => $result['allTags'],
            'current_tag' => $tag,
            'title'       => null,
            'content'     => null,
            'tags'        => [],
            'random'      => str_pad((string)mt_rand(1, 135), 3, '0', STR_PAD_LEFT),
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_post', methods: ['GET'])]
    public function blogPost(string $slug, BlogService $blogService): Response
    {
        try {
            [$metadata, $markdown] = $blogService->getBlogPost($slug);
            $parsedown = new Parsedown();
            $htmlContent = $parsedown->text($markdown);
        } catch (Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->render('blog/blog.html.twig', [
            'content'     => $htmlContent,
            'title'       => $metadata['title'] ?? ucfirst($slug),
            'tags'        => $metadata['tags'] ?? [],
            'posts'       => null,
            'all_tags'    => null,
            'current_tag' => null,
            'random'      => str_pad((string)mt_rand(1, 135), 3, '0', STR_PAD_LEFT),
        ]);
    }

    #[Route('/api/v1/blog', name: 'api_v1_blog_list', methods: ['GET'])]
    public function apiBlogList(Request $request, BlogService $blogService): JsonResponse
    {
        return new JsonResponse($blogService->getBlogPosts($request->query->get('tag'))['posts'] ?? []);
    }

    #[Route('/feed', name: 'rss_blog_feed', methods: ['GET'])]
    public function rssBlogFeed(ReallySimpleSyndicationService $rssService, BlogService $blogService): Response
    {
        $posts = $blogService->getBlogPosts()['posts'] ?? [];

        foreach ($posts as &$post) {
            $post['link'] = $this->generateUrl('blog_post', ['slug' => $post['slug']], UrlGeneratorInterface::ABSOLUTE_URL);
            $post['date'] = $post['date'] ?? date('Y-m-d H:i:s');
            $post['description'] = $post['description'] ?? '';
        }

        $rssFeed = $rssService->generateFeed(
            $posts,
            $this->generateUrl('blog_page', [], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        return new Response($rssFeed, 200, ['Content-Type' => 'application/rss+xml']);
    }
}