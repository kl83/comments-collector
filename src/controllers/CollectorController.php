<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\base\InvalidConfigException;
use app\core\CollectorComponent;

/**
 * Comments collector
 */
class CollectorController extends Controller
{
    public $defaultAction = 'collect';

    private function getComponent(): CollectorComponent
    {
        return Yii::$app->collector;
    }

    /**
     * Collect comments
     */
    public function actionCollect()
    {
        $collector = $this->getComponent();
        foreach ($collector->sources as $source) {
            $this->stdout('Grab from ' . $source->getTitle() . ' source' . PHP_EOL, Console::FG_CYAN);
            try {
                $comments = $source->grabComments();
                if (count($comments)) {
                    $this->stdout('Found ' . count($comments) . ' comments' . PHP_EOL);
                    $validComments = $comments->getValid();
                    if (count($validComments)) {
                        $this->stdout(count($validComments) . ' comments is valid' . PHP_EOL);
                        $savedCommentsCount = $collector->storage->save($comments);
                        if ($savedCommentsCount) {
                            $this->stdout($savedCommentsCount . ' new comments saved' . PHP_EOL);
                        } else {
                            $this->stdout('No new comments found' . PHP_EOL);
                        }
                    } else {
                        $this->stdout('No valid comments found' . PHP_EOL);
                    }
                } else {
                    $this->stdout('No comments found' . PHP_EOL);
                }
            } catch (InvalidConfigException $exception) {
                $this->stderr($exception->getMessage() . PHP_EOL, Console::FG_RED);
            }
        }
    }
}
