<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Class ReallySimpleSyndicationService
 * @package App\Service
 */
class ReallySimpleSyndicationService
{
    /**
     * Generate the RSS feed
     *
     * @param array $posts
     * @param string $link
     * @return string
     */
    public function generateFeed(array $posts, string $link): string
    {
        $rssFeed = '<?xml version="1.0" encoding="UTF-8" ?>';
        $rssFeed .= '<rss version="2.0">';
        $rssFeed .= '<channel>';
        $rssFeed .= '<title>Your Blog Title</title>';
        $rssFeed .= '<link>' . $link . '</link>';
        $rssFeed .= '<description>Your blog description</description>';

        foreach ($posts as $post) {
            $rssFeed .= '<item>';
            $rssFeed .= '<title>' . htmlspecialchars($post['title']) . '</title>';
            $rssFeed .= '<link>' . htmlspecialchars($post['link']) . '</link>';
            $rssFeed .= '<description>' . htmlspecialchars($post['description']) . '</description>';
            $rssFeed .= '<pubDate>' . date(DATE_RSS, strtotime($post['date'])) . '</pubDate>';
            $rssFeed .= '</item>';
        }

        $rssFeed .= '</channel>';
        $rssFeed .= '</rss>';

        return $rssFeed;
    }
}