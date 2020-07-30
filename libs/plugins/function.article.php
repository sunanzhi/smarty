
<?php

use backend\modules\moduleapi\models\theme\ThemeCategory;
use backend\modules\moduleapi\models\theme\ThemeContent;
use common\Cms;
use common\components\HomeController;
use common\models\Category;
use common\models\Content;
use common\models\WebsiteConfig;

/**
 * 获取文章列表，并将内容保存到对应的`var`变量中
 * User: lin.zhou
 * Date: 2018/3/1
 * Time: 15:49
 */
function smarty_function_article($params, Smarty_Internal_Template $tpl)
{
    if (!key_exists('id', $params)&&!key_exists('alias', $params)) {
        return '';
    }
    // 修改模板
    $updateTemplate = Cms::getGetValue('updateTemplate', false);
    $themeId = WebsiteConfig::getModel(WebsiteConfig::CONFIG_THEME);

    $params['size'] = $params['size'] ? $params['size'] : 1;
    if(key_exists('alias', $params)){
        if ($updateTemplate) {
            $category = ThemeCategory::find()->where(['theme_id' => $themeId, 'url_alias' => $params['alias']])->one();
        } else {
            $websiteId = \common\components\BaseActiveRecord::getWebsiteId();
            $category = \common\models\Category::find()->where(['website_id'=>$websiteId,'url_alias'=>$params['alias'], 'status' => Category::STATUS_ON])->one();
        }

        if(!$category)
            return '';
        $params['id'] = $category->id;
    }

    if ($updateTemplate) {
        $model = new ThemeContent();
        $data = $model->getList($params['id'], $params['size']);
    } else {
        $model = new Content();
        $data = $model->getList($params['id'], $params['size']);
    }


    if (YII_DEV) {
        $domain = 'http://cms.dev.yingxiong.com/content/update?id=';
    } elseif (YII_DEMO) {
        $domain = 'http://cms2.demo.yingxiong.com/content/update?id=';
    } else {
        $domain = 'http://cms.yingxiong.com/content/update?id=';
    }
    if ($data && !empty($data)) {
        foreach ($data as &$v) {
            $v['jumpbackend'] = $domain.$v['id'];
        }
    }
    $tpl->tpl_vars[$params['var']] = new Smarty_Variable(null, $tpl->isRenderingCache);
    $tpl->tpl_vars[$params['var']]->value = $data;
    return '';
}
