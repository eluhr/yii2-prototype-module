<?php
namespace dmstr\modules\prototype\assets;

/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use dmstr\modules\prototype\models\Less;
use yii\caching\FileDependency;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\AssetBundle;

class DbAsset extends AssetBundle
{
    const CACHE_ID = 'app\assets\SettingsAsset';
    const SETTINGS_KEY = 'app.less';
    const MAIN_LESS_FILE = 'main.less';
    const SETTINGS_SECTION = 'app.assets';

    public $sourcePath = '@runtime/settings-asset';
    public $tmpPath = '@runtime/settings-asset-tmp';

    public $settingsKey = 'registerPrototypeAssetKey';

    public $depends = [
        'yii\web\YiiAsset',
        // if a full BootstrapAsset (CSS) is compiled, it's recommended to disable it in assetManager configuration
        'yii\bootstrap\BootstrapPluginAsset', // (JS)
    ];

    public function init()
    {
        $this->css[] = \Yii::$app->settings->get($this->settingsKey, self::SETTINGS_SECTION).'-'.self::MAIN_LESS_FILE;

        parent::init();

        if (!$this->sourcePath) {
            // TODO: this is workaround for empty source path when using bundled assets
            return;
        } else {
            $sourcePath = \Yii::getAlias($this->sourcePath);

            $models = Less::find()->all();
            $hash = sha1(Json::encode($models));
            if (!is_dir($sourcePath) || ($hash !== \Yii::$app->cache->get(self::CACHE_ID))) {
                $tmpPath = uniqid($sourcePath.'-');
                FileHelper::createDirectory($tmpPath);

                foreach ($models as $model) {
                    file_put_contents("$tmpPath/{$model->key}.less", $model->value);
                }

                $dependency = new FileDependency();
                $dependency->fileName = __FILE__;
                \Yii::$app->cache->set(self::CACHE_ID, $hash, 0, $dependency);

                // force republishing of asset files by Yii Framework
                FileHelper::removeDirectory($sourcePath);
                rename($tmpPath, $sourcePath);
            }
        }
    }
}
