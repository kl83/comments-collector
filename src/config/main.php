<?php

use yii\helpers\ArrayHelper;

return [
    'id' => 'comments-collector',
    'basePath' => __DIR__ . '/..',
    'components' => [
        'collector' => ArrayHelper::merge(
            require __DIR__ . '/../../config.php',
            ['class' => 'app\core\CollectorComponent']
        ),
    ],
];
