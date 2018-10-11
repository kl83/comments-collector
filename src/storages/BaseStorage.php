<?php

namespace app\storages;

use yii\base\BaseObject;
use app\core\CommentsCollection;

abstract class BaseStorage extends BaseObject
{
    public abstract function save(CommentsCollection $comments): int;
}
