<?php
use yii\helpers\Html;

/**
 *
 * Created by PhpStorm.
 * User: lin.zhou
 * Date: 2017/6/27
 * Time: 15:49
 */
function smarty_function_csrf()
{
    return Html::csrfMetaTags();
}