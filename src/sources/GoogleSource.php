<?php

namespace app\sources;

use DateTime;
use yii\helpers\ArrayHelper;

class GoogleSource extends BaseSource
{
    public function getId(): string
    {
        return 'google';
    }

    public function getTitle(): string
    {
        return 'Google';
    }

    protected function grab()
    {
        $content = file_get_contents($this->url);
        $data = json_decode($content);
        $reviews = ArrayHelper::getValue($data, 'result.reviews');
        foreach ($reviews as $review) {
            $datetime = new DateTime();
            $datetime->setTimestamp(ArrayHelper::getValue($review, 'time'));
            $this->addComment([
                'datetime' => $datetime,
                'userName' => ArrayHelper::getValue($review, 'author_name'),
                'text' => ArrayHelper::getValue($review, 'text'),
            ]);
        }
    }
}
