<?php

use common\Cms;
use common\models\Category;
use yii\helpers\Url;

/**
 * 生成URL
 *
 * Created by PhpStorm.
 * User: lin.zhou
 * Date: 2017/6/27
 * Time: 15:49
 */
function smarty_function_myurl($url = '')
{
    $arr = explode(',', $url['name']);

    $routeTmp = ['article/list', 'article/detail','article/news', 'wap/list', 'image/list', 'video/list', 'wap/video/list', 'wap/image/list', 'wap/article/list'];
    if (in_array($arr[0], $routeTmp)) {
        $params = array();
        foreach ($arr as $k => $v) {
            if ($k == 0) {
                continue;
            }
            $tmp = explode('=>', $v);
            $params[trim($tmp[0])] = trim($tmp[1]);
        }
 
        switch ($arr[0]){
            case 'article/list':
            case 'wap/article/list':
                if (key_exists('id', $params)) {
                    $category = Category::findOne($params['id']);
                 $params['cat_dir'] = key_exists('cat_dir', $params) ? $params['cat_dir'] :( $category['url_alias'] ? $category['url_alias'] : '');
                }elseif(key_exists('alias',$params)){
                    $websiteId = \common\components\BaseActiveRecord::getWebsiteId();
                    $category = Category::find()->where(['website_id'=>$websiteId,'url_alias'=>$params['alias']])->one();
                    if($category){
                        $params['cat_dir'] = key_exists('cat_dir', $params) ? $params['cat_dir'] :( $category['url_alias'] ? $category['url_alias'] : '');
                        $params['id'] = $category->id;
                    }
                }
                break;
        }
        return Cms::getUrl($arr[0], $params);
    }
    $params[] = trim($arr[0]);
    foreach ($arr as $k => $v) {
        if ($k == 0) {
            continue;
        }
        $tmp = explode('=>', $v);
        $params[trim($tmp[0])] = trim($tmp[1]);
    }
    return Url::to($params);

}