<?php

namespace cmsadmin\apis;

use cmsadmin\models\NavItemRedirect;
use Yii;
use cmsadmin\models\Property;
use cmsadmin\models\Nav;
use cmsadmin\models\NavItem;

/**
 * example.com/admin/api-cms-nav/create-page
 * example.com/admin/api-cms-nav/create-item-page
 * example.com/admin/api-cms-nav/create-module
 * example.com/admin/api-cms-nav/create-item-module.
 *
 * @author nadar
 */
class NavController extends \admin\base\RestController
{
    private function postArg($name)
    {
        return Yii::$app->request->post($name, null);
    }
    
    public function actionFindNavItems($navId)
    {
        return NavItem::find()->where(['nav_id' => $navId])->asArray()->with('lang')->all();
    }

    public function actionGetProperties($navId)
    {
        $data = [];
        foreach (Property::find()->select(['admin_prop_id', 'value'])->where(['nav_id' => $navId])->asArray()->all() as $row) {
            if (is_numeric($row['value'])) {
                $row['value'] = (int) $row['value'];
            }
            $data[] = $row;
        }

        return $data;
    }

    public function actionSaveProperties($navId)
    {
        $rows = [];
        foreach (Yii::$app->request->post() as $id => $value) {
            $rows[] = [
                'nav_id' => $navId,
                'admin_prop_id' => $id,
                'value' => $value,
            ];
        }

        foreach ($rows as $atrs) {
            $model = \cmsadmin\models\Property::find()->where(['admin_prop_id' => $atrs['admin_prop_id'], 'nav_id' => $navId])->one();

            if ($model) {
                if (empty($atrs['value']) && $atrs['value'] != 0) {
                    $model->delete();
                } else {
                    // update
                    $model->value = $atrs['value'];
                    $model->update(false);
                }
            } else {
                $model = new \cmsadmin\models\Property();
                $model->attributes = $atrs;
                $model->insert(false);
            }
        }
    }

    public function actionToggleHidden($navId, $hiddenStatus)
    {
        $item = Nav::find()->where(['id' => $navId])->one();

        if ($item) {
            Yii::$app->menu->flushCache();
            $item->is_hidden = $hiddenStatus;
            $item->update(false);

            return true;
        }

        return false;
    }

    public function actionToggleHome($navId, $homeState)
    {
        $item = Nav::find()->where(['id' => $navId])->one();
        Yii::$app->menu->flushCache();
        if ($homeState == 1) {
            Nav::updateAll(['is_home' => 0]);
            $item->setAttributes([
                'is_home' => 1,
            ]);
        } else {
            $item->setAttributes([
                'is_home' => 0,
            ]);
        }

        return $item->update(false);
    }

    public function actionToggleOffline($navId, $offlineStatus)
    {
        $item = Nav::find()->where(['id' => $navId])->one();

        if ($item) {
            Yii::$app->menu->flushCache();
            $item->is_offline = $offlineStatus;
            $item->update(false);

            return true;
        }

        return false;
    }

    public function actionDetail($navId)
    {
        return \cmsadmin\models\Nav::findOne($navId);
    }

    public function actionDelete($navId)
    {
        $model = \cmsadmin\models\Nav::find()->where(['id' => $navId])->one();
        if ($model) {
            Yii::$app->menu->flushCache();
            // check for internal redirects
            $redirectResult = false;
            $redirects = NavItemRedirect::find()->where(['value' => $navId])->asArray()->all();
            foreach ($redirects as $redirect) {
                $navItem = NavItem::find()->where(['nav_item_type' => 3, 'nav_item_type_id' => $redirect['id']])->one();
                $redirectResult = empty(Nav::find()->where(['id' => $navItem->nav_id, 'is_deleted' => 0])->one()) ? $redirectResult : true;
            }

            if ($redirectResult) {
                Yii::$app->response->statusCode = 417;
                return;
            }

            $model->is_deleted = 1;

            foreach (NavItem::find()->where(['nav_id' => $navId])->all() as $navItem) {
                $navItem->setAttribute('alias', date('Y-m-d-H-i').'-'.$navItem->alias);
                $navItem->update(false);
            }

            return $model->update(false);
        }
    }

    /**
     * creates a new nav entry for the type page (nav_id will be created.
     *
     * @param array $_POST:
     */
    public function actionCreatePage()
    {
        $fromDraft = $this->postArg('from_draft_id');
        $model = new \cmsadmin\models\Nav();
        
        if (!empty($fromDraft)) {
            $create = $model->createPageFromDraft($this->postArg('parent_nav_id'), $this->postArg('nav_container_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('description'), $fromDraft, $this->postArg('is_draft'));
        } else {
            $create = $model->createPage($this->postArg('parent_nav_id'), $this->postArg('nav_container_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('layout_id'), $this->postArg('description'), $this->postArg('is_draft'));
        }
        
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }
        Yii::$app->menu->flushCache();
        return $create;
    }
    
    /**
     * creates a new nav_item entry for the type page (it means nav_id will be delivered).
     */
    public function actionCreatePageItem()
    {
        Yii::$app->menu->flushCache();
        $model = new \cmsadmin\models\Nav();
        $create = $model->createPageItem($this->postArg('nav_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('layout_id'), $this->postArg('description'));
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }

        return $create;
    }

    public function actionCreateModule()
    {
        Yii::$app->menu->flushCache();
        $model = new \cmsadmin\models\Nav();
        $create = $model->createModule($this->postArg('parent_nav_id'), $this->postArg('nav_container_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('module_name'), $this->postArg('description'));
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }

        return $create;
    }

    public function actionCreateModuleItem()
    {
        Yii::$app->menu->flushCache();
        $model = new \cmsadmin\models\Nav();
        $create = $model->createModuleItem($this->postArg('nav_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('module_name'), $this->postArg('description'));
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }

        return $create;
    }

    /* redirect */

    public function actionCreateRedirect()
    {
        Yii::$app->menu->flushCache();
        $model = new \cmsadmin\models\Nav();
        $create = $model->createRedirect($this->postArg('parent_nav_id'), $this->postArg('nav_container_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('redirect_type'), $this->postArg('redirect_type_value'), $this->postArg('description'));
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }

        return $create;
    }
    
    public function actionCreateRedirectItem()
    {
        Yii::$app->menu->flushCache();
        $model = new \cmsadmin\models\Nav();
        $create = $model->createRedirectItem($this->postArg('nav_id'), $this->postArg('lang_id'), $this->postArg('title'), $this->postArg('alias'), $this->postArg('redirect_type'), $this->postArg('redirect_type_value'), $this->postArg('description'));
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }
    
        return $create;
    }
    
    public function actionCreateFromPage()
    {
        Yii::$app->menu->flushCache();
        $model = new \cmsadmin\models\Nav();
        $create = $model->createItemLanguageCopy($this->postArg('id'), $this->postArg('toLangId'), $this->postArg('title'), $this->postArg('alias'));
        if ($create !== true) {
            Yii::$app->response->statusCode = 422;
        }
        
        return $create;
    }
}
