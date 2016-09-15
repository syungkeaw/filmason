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
class TypeaheadAsset extends AssetBundle
{
    public $sourcePath = '@bower/typeahead.js/dist';
    public $css = [
    ];
    public $js = [
        'http://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.3/jquery.xdomainrequest.min.js',
    	'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js',
        // 'http://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js',
        // 'typeahead.bundle.min.js',
    ];
    public $depends = [
    ];
}
