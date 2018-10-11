<?php

namespace app\sources;

use DateTime;
use app\core\HtmlFinder;

class YandexSource extends BaseSource
{
    public function getId(): string
    {
        return 'yandex';
    }

    public function getTitle(): string
    {
        return 'Yandex';
    }

    protected function grab()
    {
        $finder = new HtmlFinder([
            'url' => $this->url,
            'nodesXPath' => '//div[@class="reviews__collections"]/div[contains(@class, "review")]',
        ]);
        foreach ($finder->nodes as $node) {
            $datetime = $finder->getAttribute(
                $node,
                './/meta[@itemprop="datePublished"]',
                'content'
            );
            $this->addComment([
                'datetime' => new DateTime($datetime),
                'userName' => $finder->getText($node, './/div[@class="review__user-name"]'),
                'text' => $finder->getText($node, './/div[@class="review__description"]'),
            ]);
        }
    }
}
