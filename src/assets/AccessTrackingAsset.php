<?php

namespace dmstr\modules\prototype\assets;

use yii\web\AssetBundle;

class AccessTrackingAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/web/access-tracking';

    public $js = [
        'access-tracking.js'
    ];
}
