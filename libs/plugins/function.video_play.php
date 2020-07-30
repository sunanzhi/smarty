<?php

/**
 * 生成视频播放小部件
 *
 * Created by PhpStorm.
 * User: lin.zhou
 * Date: 2017/6/27
 * Time: 15:49
 */
function smarty_function_video_play()
{
    return \common\widgets\videoPlay\VideoPlayWidget::widget();
}