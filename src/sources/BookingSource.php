<?php

namespace app\sources;

use DateTime;
use app\core\HtmlFinder;

class BookingSource extends BaseSource
{
    public function getId(): string
    {
        return 'booking';
    }

    public function getTitle(): string
    {
        return 'Booking.com';
    }

    protected function grab()
    {
        $finder = new HtmlFinder([
            'url' => $this->url,
            'nodesXPath' => '//ul[@class="review_list"]/li[@itemprop="review"]',
        ]);
        foreach ($finder->nodes as $node) {
            $datetime = $finder->getAttribute(
                $node,
                './/meta[@itemprop="datePublished"]',
                'content'
            );
            $positive = $finder->getText($node, './/p[@class="review_pos "]//span');
            $negative = $finder->getText($node, './/p[@class="review_neg "]//span');
            if ($positive && $negative) {
                $positive = '<div class="positive-text">' . $positive . '</div>';
                $negative = '<div class="negative-text">' . $negative . '</div>';
            }
            $this->addComment([
                'datetime' => new DateTime($datetime),
                'userName' => $finder->getText($node, './/h4//*[@itemprop="name"]'),
                'text' => $positive . $negative,
                'maxRating' => 10,
                'rating' => $finder->getText($node, './/span[@class="review-score-badge"]'),
            ]);
        }
    }
}
