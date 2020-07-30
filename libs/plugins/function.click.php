<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9
 * Time: 9:25
 */


//生成URL地址

function smarty_function_click($id)
{

    return \common\widgets\click\ClickWidget::widget();
}