<?php

namespace app\sources;

use yii\base\BaseObject;
use yii\helpers\Inflector;
use yii\base\InvalidConfigException;
use app\core\Comment;
use app\core\CommentsCollection;

/**
 * Represents the source URL and base methods for grab comments
 */
abstract class BaseSource extends BaseObject
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var CommentsCollection
     */
    protected $_collection;

    /**
     * Source ID. "google", "facebook", etc...
     * @return string
     */
    public abstract function getId(): string;

    /**
     * Source title. "Google", "Facebook", etc...
     * @return string
     */
    public abstract function getTitle(): string;

    /**
     * Grabs comments
     */
    protected abstract function grab();

    public function init()
    {
        parent::init();
        $this->_collection = new CommentsCollection();
    }

    public function grabComments(): CommentsCollection
    {
        if (!$this->url) {
            throw new InvalidConfigException('Error! Configure ' . $this->getTitle() . ' source url');
        }
        $this->grab();
        return $this->_collection;
    }

    protected function addComment(array $commentCfg)
    {
        $commentCfg['sourceId'] = $this->getId();
        $commentCfg['sourceTitle'] = $this->getTitle();
        if (empty($commentCfg['id'])) {
            $commentCfg['id'] = implode('-', [
                $commentCfg['sourceId'],
                $commentCfg['datetime']->format('Ymd'),
                Inflector::slug($commentCfg['userName']),
                crc32($commentCfg['text']),
            ]);
        }
        $comment = new Comment($commentCfg);
        $this->_collection->add($comment);
    }
}
