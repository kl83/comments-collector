<?php

namespace app\core;

use DateTime;
use yii\base\Model;

class Comment extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $sourceId;

    /**
     * @var string
     */
    public $sourceTitle;

    /**
     * @var int
     */
    public $rating;

    /**
     * @var int
     */
    public $maxRating;

    /**
     * @var DateTime
     */
    public $datetime;

    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $text;

    public function rules(): array
    {
        return [
            [['id', 'sourceId', 'datetime', 'userName', 'text'], 'required'],
            [['rating', 'maxRating'], 'integer'],
        ];
    }
}
