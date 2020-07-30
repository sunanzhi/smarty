
<?php

use backend\modules\moduleapi\models\theme\ThemeCategory;
use backend\modules\moduleapi\models\theme\ThemeContent;
use common\Cms;
use common\components\HomeController;
use common\models\Category;
use common\models\Content;
use common\models\WebsiteConfig;

/**
 * 获取分类列表
 * @param $params
 * @param Smarty_Internal_Template $tpl
 * @return string
 */
function smarty_function_category($params, Smarty_Internal_Template $tpl)
{
    if (!key_exists('id', $params)) {
        return '';
    }
    $websiteId = \common\components\BaseActiveRecord::getWebsiteId();
    $data = Category::find()->where(['website_id'=>$websiteId,'parent_id'=>$params['id'], 'status' => Category::STATUS_ON])->asArray()->all();
    if(!$data)
        return '';
    if(key_exists('is_children', $params)){
        $level = isset($params['leve'])?$params['leve']:2;
        _getChild($websiteId,$data,$level);
    }

    $tpl->tpl_vars[$params['var']] = new Smarty_Variable(null, $tpl->isRenderingCache);
    $tpl->tpl_vars[$params['var']]->value = $data;
    return '';
}

function _getChild($websiteId,&$list,$level=1,$currentLevel=1){
    if($level>=$currentLevel){
        foreach ($list as &$d){
            $d['child'] = Category::find()->where(['website_id'=>$websiteId,'parent_id'=>$d['id'], 'status' => Category::STATUS_ON])->asArray()->all();
        }
        $currentLevel++;
        if($level>$currentLevel)
            _getChild($list,$level,$currentLevel);
    }
}