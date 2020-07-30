<?php

use common\Cms;
use common\models\Category;
use common\models\Content;
use common\models\WebsiteConfig;
use \common\components\BaseView;

/**
 * 生成URL
 *
 * Created by PhpStorm.
 * User: lin.zhou
 * Date: 2017/6/27
 * Time: 15:49
 */
function smarty_function_seo($name = '')
{
    $urlAction = \Yii::$app->controller->id.'/'.\Yii::$app->controller->action->id;
    $websiteId = \common\components\BaseActiveRecord::getWebsiteId();
    $url= str_replace('wap/', '', $_SERVER['REQUEST_URI']);
    $route = str_replace('wap/', '', $urlAction);
    //赤潮
    if($websiteId==28){
        if(BaseView::strPos($url,'site/index')){
            $url = 'site/index';
        }else if(BaseView::strPos($url,'info/xinwen/list')){
            $url='article/list_xinwen';
        }else if(BaseView::strPos($url,'info/gg/list')){
            $url='article/list_gg';
        }else if(BaseView::strPos($url,'video')){
            $url='video/index';
        }else if(BaseView::strPos($url,'info/gonglue')){
            $url='article/list_gonglue';
        }else if(BaseView::strPos($url,'info/xsgl')){
            $url='article/list_xsgl';
        }else if(BaseView::strPos($url,'info/jjgl')){
            $url='article/list_jjgl';
        }else if(BaseView::strPos($url,'info/zhanzheng')){
            $url = 'info/zhanzheng';
        } else if(BaseView::strPos($url,'info/faq')){
            $url = 'info/faq';
        }else if(BaseView::strPos($url,'/m')){
            $url = 'site/index';
        }else if(BaseView::strPos($url,'/')){
            $url = 'site/index';
        }else{
            $url = 'all';
        }
    } else if($websiteId==25){ //九剑
        if(BaseView::strPos($url,'m')){
            $url = '/';
        }
    }else if($websiteId==26){ //创造与魔法
        if(BaseView::strPos($url,'site/index')){
            $url = 'site/index';
        }else if(BaseView::strPos($url,'info/all/list')){
            $categoryId = Cms::getGetValue('cid');
            if($categoryId==64){
                $url='article/list_all';
            }else if($categoryId==65){
                $url='article/list_news';
            }else if($categoryId==66){
                $url='article/list_gg';
            }else if($categoryId==67){
                $url='article/list_huodong';
            }
        }else if(BaseView::strPos($url,'article/data')){
            $categoryId = Cms::getGetValue('id');
            if($categoryId==117){
                $url='article/data_scycz';
            }else if($categoryId==118){
                $url='article/data_prtx';
            }else if($categoryId==119){
                $url='article/data_mftx';
            }else if($categoryId==120){
                $url='article/data_swtx';
            }else if($categoryId==121){
                $url='article/data_yqwf';
            }else{
                $url='article/data';
            }
        }else if(BaseView::strPos($url,'info/raiders/list')){
            $categoryId = Cms::getGetValue('id');
            if($categoryId==132){
                $url='article/raiders_jcyd';
            }else if($categoryId==135){
                $url='article/raiders_prgl';
            }else if($categoryId==138){
                $url='article/raiders_mfgl';
            }else if($categoryId==139){
                $url='article/raiders_swgl';
            }else if($categoryId==140){
                $url='article/raiders_jzgl';
            }
        }else if(BaseView::strPos($url,'article/raiders')){
            $url='article/raiders_jcyd';
        }else {
            $url = 'all';
        }
    }
    $category = Category::find()->where(['url_alias' => $url, 'website_id' => \common\components\BaseActiveRecord::getWebsiteId()])->asArray()->one();
    $seoTitle = '';
    $seoDescription = '';
    $seoKeywords = '';
    $language=Cms::getSession('language');
    if ($category && $category['seo_title']) {
        if($language=='zh_cn') {
            $seoTitle = $category['seo_title'];
            $seoDescription = $category['seo_description'];
            $seoKeywords = $category['seo_keywords'];
        }else{
            $seoTitle = $category['seo_en_title'];
            $seoDescription = $category['seo_en_description'];
            $seoKeywords = $category['seo_en_keywords'];
        }
    }

    //新闻列表
    if ($route == 'article/detail' || $route == 'site/detail' || $route == 'site/details') {
        $id = Cms::getGetValue('id') ? Cms::getGetValue('id') : Cms::getGetValue('aid');

        $content = Content::findOne(['old_id' => $id]);

        if (!$content) {
            $content = Content::findOne(['id' => $id])->attributes;
        } else {
            $content = $content->attributes;
        }
        $seoTitle = $content['seo_title'];
        $seoDescription = $content['seo_description'];
        $seoKeywords = $content['seo_keywords'];
    }
    $allSeo = Category::find()->where(['url_alias' => 'all', 'website_id' => \common\components\BaseActiveRecord::getWebsiteId()])->asArray()->one();

    if (!$seoTitle && !empty($allSeo)) {
        $seoTitle = $allSeo['seo_title'];
    }
    if (!$seoDescription && !empty($allSeo)) {
        $seoDescription = $allSeo['seo_description'];
    }
    if (!$seoKeywords && !empty($allSeo)) {
        $seoKeywords = $allSeo['seo_keywords'];
    }
    if (!$seoTitle) {
        $seoTitle = WebsiteConfig::getModel(\common\models\WebsiteConfig::CONFIG_WEB_NAME, true);
    }
    if (!$seoDescription) {
        $seoDescription = WebsiteConfig::getModel(\common\models\WebsiteConfig::CONFIG_WEB_DESCRIPTION, true);
    }
    if (!$seoKeywords) {
        $seoKeywords = WebsiteConfig::getModel(\common\models\WebsiteConfig::CONFIG_WEB_KEYWORDS, true);
    }
    $data['seoTitle'] = $seoTitle;
    $data['seoDescription'] = $seoDescription;
    $data['seoKeywords'] = $seoKeywords;
    if ($name['name'] == 'title') {
        return $seoTitle;
    } else if ($name['name'] == 'keywords') {
        return $seoKeywords;

    } else if ($name['name'] == 'description') {
        return $seoDescription;
    }
}