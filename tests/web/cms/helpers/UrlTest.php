<?php

namespace tests\web\cms\helpers;

use Yii;
use cms\helpers\Url;

class UrlTest extends \tests\web\Base
{
    public function testToModule()
    {
        Yii::$app->request->baseUrl = '';
        Yii::$app->request->scriptUrl = '';
        
        $this->assertEquals('/en/news-module', Url::toModule('news'));
        $this->assertEquals('notexists', Url::toModule('notexists'));
    }

    public function testToModuleRoute()
    {
        Yii::$app->request->baseUrl = '';
        Yii::$app->request->scriptUrl = '';

        $this->assertEquals('/en/news-module/1/foo-bar', Url::toModuleRoute('news', 'news/default/detail', ['id' => 1, 'title' => 'foo-bar']));
    }
}
