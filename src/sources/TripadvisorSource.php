<?php

namespace app\sources;

use DateTime;
use yii\httpclient\Client;
use app\core\HtmlFinder;

class TripadvisorSource extends BaseSource
{
    public function getId(): string
    {
        return 'tripadvisor';
    }

    public function getTitle(): string
    {
        return 'TripAdvisor';
    }

    private function grabCommentsContent(array $id): string
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://www.tripadvisor.ru/OverlayWidgetAjax?Mode=EXPANDED_HOTEL_REVIEWS_RESP&metaReferer=')
            ->addHeaders([
                'referer' => $this->url,
            ])
            ->setData([
                'reviews' => implode(',', $id),
                'contextChoice' => 'DETAIL_HR',
                'haveJses' => 'earlyRequireDefine,amdearly,global_error,long_lived_global,apg-Hotel_Review,apg-Hotel_Review-in,bootstrap,desktop-rooms-guests-dust-ru,responsive-calendar-templates-dust-ru,@ta/common.global,@ta/common.styleguide,@ta/common.overlays,social.share-cta,@ta/common.media,cross-sells.results-from-featured,@ta/public.maps,hotels.hotel-review-overview,hotels.hotel-review-roomtips,hotels.hotel-review-photos,@ta/trips.save-to-trip,@ta/platform.runtime,masthead_search_late_load,taevents,p13n_masthead_search__deferred__lateHandlers',
                'haveCsses' => 'apg-Hotel_Review-in,responsive_calendars_corgi',
                'Action' => 'install'
            ])
            ->send();
        return '<meta http-equiv="content-type" content="text/html; charset=utf-8">' .
            $response->toString();
    }

    private function grabCommentsId(): array
    {
        $finder = new HtmlFinder([
            'url' => $this->url,
            'nodesXPath' => '//div[@id="taplc_location_reviews_list_resp_hr_resp_0"]//div[@class="review-container"]',
        ]);
        $commentId = [];
        foreach ($finder->nodes as $node) {
            $commentId[] = $finder->getAttribute(
                $node,
                './/div[@class="reviewSelector"]',
                'data-reviewid'
            );
        }
        return $commentId;
    }

    private function parseDate(string $date): DateTime
    {
        $months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
            'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        $date = str_replace($months, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], $date);
        $date = preg_replace(
            '~^[^\d]*(\d+)\s+(\d+)\s+(\d+)[^\d]*$~',
            '$3-$2-$1',
            $date
        );
        return new DateTime($date);
    }

    protected function grab()
    {
        $id = $this->grabCommentsId();
        $finder = new HtmlFinder([
            'content' => $this->grabCommentsContent($id),
            'nodesXPath' => '//div[@class="reviewSelector"]',
        ]);
        foreach ($finder->nodes as $node) {
            $date = $finder->getAttribute(
                $node,
                './/span[@class="ratingDate"]',
                'title'
            );
            $ratingAttr = $finder->getAttribute($node, './/span[contains(@class, "ui_bubble_rating")]', 'class');
            $rating = preg_replace('~^.*(\d+)$~', '$1', $ratingAttr);
            $this->addComment([
                'datetime' => $this->parseDate($date),
                'userName' => $finder->getText($node, './/div[@class="member_info"]//div[@class="info_text"]//div'),
                'text' => $finder->getText($node, './/p[@class="partial_entry"]'),
                'maxRating' => 5,
                'rating' => $rating / 10,
            ]);
        }
    }
}
