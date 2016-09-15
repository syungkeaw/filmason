<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class JqueryAsset extends AssetBundle
{
    public $js = [
        // 'handlebars.js/handlebars.min.js',
    	'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',
    ];
    public $depends = [
    ];
}
