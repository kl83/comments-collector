<?php

namespace app\core;

use Yii;
use yii\base\BaseObject;
use app\sources\BaseSource;
use app\storages\BaseStorage;

class CollectorComponent extends BaseObject
{
    /**
     * @var BaseSource[]
     */
    public $sources;

    /**
     * @var BaseStorage
     */
    public $storage;

    public function init()
    {
        parent::init();
        foreach ($this->sources as &$source) {
            $source = Yii::createObject($source);
        }
        $this->storage = Yii::createObject($this->storage);
    }
}
