<?php

use backend\modules\moduleapi\models\theme\ThemeFragment;
use common\Cms;
use common\components\BaseActiveRecord;
use common\models\Fragment;
use common\models\WebsiteConfig;

/**
 * 获取推荐位列表，并将内容保存到对应的`var`变量中
 * User: lin.zhou
 * Date: 2018/3/1
 * Time: 15:49
 */
function smarty_function_fragment($params, Smarty_Internal_Template $tpl)
{
    if (!key_exists('code', $params)) {
        return '';
    }

    // 修改模板
    $updateTemplate = Cms::getGetValue('updateTemplate', false);
    $params['size'] = $params['size'] ? $params['size'] : 1;

    $themeId = WebsiteConfig::getModel(WebsiteConfig::CONFIG_THEME);
    if ($updateTemplate) {
        $model = new ThemeFragment();
        $data = $model->getRecommend($themeId, $params['code'], $params['size']);
    } else {
        $model = new Fragment();
        $data = $model->getRecommend($params['code'], $params['size']);
    }

    if (YII_DEV) {
        $domain = 'http://cms.dev.yingxiong.com/fragment-html-data/index?fragment_id=';
    } elseif (YII_DEMO) {
        $domain = 'http://cms2.demo.yingxiong.com/fragment-html-data/index?fragment_id=';
    } else {
        $domain = 'http://cms.yingxiong.com/fragment-html-data/index?fragment_id=';
    }
    if ($data && !empty($data)) {
        foreach ($data as &$v) {
            if ($updateTemplate) {
                $v['jumpbackend'] = $domain.$v['theme_fragment_id'];
            } else {
                $v['jumpbackend'] = $domain.$v['fragment_id'];
            }

        }
    } elseif ($themeId > 0) {
        $websiteId = BaseActiveRecord::getWebsiteId();
        $fragment = Fragment::find()->where('code = :code AND website_id = :website_id', [':code' => $params['code'], ':website_id' => $websiteId])->one();
        $data[] = [
            'id' => 0,
            'title' => '',
            'sub_title' => '',
            'thumb' => '//cdnimg01.yingxiong.com/M00/0D/5C/ChpCl1vumUiEPrhlAAAAAJsrRnc006.jpg',
            'url' => '',
            'jumpbackend' => $domain.$fragment['id'],
            'target' => 1,
        ];
    }
    $tpl->tpl_vars[$params['var']] = new Smarty_Variable(null, $tpl->isRenderingCache);
    $tpl->tpl_vars[$params['var']]->value = $data;
    return '';
}
