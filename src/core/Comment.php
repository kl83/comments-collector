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

    /**
     * @var float
     */
    private $_rating;

    public function setRating($value)
    {
        $this->_rating = (float)str_replace(',', '.', trim($value));
    }

    public function getRating(): float
    {
        return $this->_rating;
    }

    public function getNormalizedRating(int $maxRating): int
    {
        return round($this->rating * ($maxRating / $this->maxRating));
    }

    public function rules(): array
    {
        return [
            [['id', 'sourceId', 'datetime', 'userName', 'text'], 'required'],
            [['rating', 'maxRating'], 'integer'],
        ];
    }
}
