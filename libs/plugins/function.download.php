<?php
use yii\helpers\Url;

/**
 * 生成URL
 *
 * Created by PhpStorm.
 * User: lin.zhou
 * Date: 2017/6/27
 * Time: 15:49
 */
function smarty_function_download()
{
    return \common\widgets\downloadLink\DownloadLinkWidget::widget();
}