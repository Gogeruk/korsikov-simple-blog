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
        $rssFeed .= '<title>Mykyta\'s Website</title>';
        $rssFeed .= '<link>' . $link . '</link>';
        $rssFeed .= '<description>Mykyta Korsikov\'s blog website about Indo-European history, Christianity, philosophy, the occult, esoterica, fantasy, mythology, internet privacy and culture, mysticism, magic, and paganism. Mykyta Korsikov, Korsikov Mykyta, Korsikov, Mykyta, Nick Korsikov, Korsikov Nick, PeepeePoopoo, fun, send help, cats === dogs, time, memes, description, SEO, ??? and so on... </description>';

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