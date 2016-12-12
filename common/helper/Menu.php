<?php

namespace common\helper;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $m_id
 * @property integer $parent_id
 * @property integer $level
 * @property string $label
 * @property string $url
 * @property string $controller
 * @property string $action
 * @property integer $sort_on
 * @property string $icon_class
 * @property integer $is_visible
 * @property integer $is_delete
 * @property string $created_on
 * @property string $modified_on
 */
class Menu extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent_id', 'level', 'sort_on', 'is_visible', 'is_delete'], 'integer'],
            [['created_on', 'modified_on'], 'safe'],
            [['label', 'url'], 'string', 'max' => 1000],
            [['controller', 'action'], 'string', 'max' => 500],
            [['icon_class'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'm_id' => Yii::t('app', 'M ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'level' => Yii::t('app', 'Level'),
            'label' => Yii::t('app', 'Label'),
            'url' => Yii::t('app', 'Url'),
            'controller' => Yii::t('app', 'Controller'),
            'action' => Yii::t('app', 'Action'),
            'sort_on' => Yii::t('app', 'Sort On'),
            'icon_class' => Yii::t('app', 'Icon Class'),
            'is_visible' => Yii::t('app', 'Is Visible'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    public function getMenuList($currentController) {
        $parentMenus = $this->getParentMenuOfMainMenu('dashboard');
        $items = [];
        foreach ($parentMenus as $k => $v) {
            if ($v->url != "#") {
                $v->url = AppHelper::param('siteUrl') . $v->url;
            }
            $items['items'][$k] = ['label' => $v->label, 'icon' => $v->icon_class, 'url' => $v->url];
            $this->getMenuItems($v->m_id, $currentController, $items['items'][$k]);
        }
        return $items;
    }

    public function getMenuItems($parentID, $currentController, &$items) {
        $itemsData = $this->hasChildren($parentID, false);
        foreach ($itemsData as $k => $v) {
            if ($v->url != "#") {
                $v->url = AppHelper::param('siteUrl') . $v->url;
            }
            $items['items'][$k] = ['label' => $v->label, 'icon' => $v->icon_class, 'url' => $v->url];
            if ($this->hasChildren($v->m_id, true)) {
                $items['items'][$k][] = $this->getMenuItems($v->m_id, $currentController, $items['items'][$k]);
            }
        }
    }

    public function getParentMenuOfMainMenu($module) {

        $data = $this->findOne(array('label' => $module, 'parent_id' => '0', 'level' => '0'));
        return $this->find()
                        ->where('parent_id != 0 AND level = 0 AND is_visible != 0 AND is_delete != 0 AND parent_id = ' . $data->m_id)
                        ->orderBy('parent_id,sort_on asc')
                        ->all();
    }

    public function hasChildren($mID, $returnFlag = true) {
        $data = $this->find()
                ->where('parent_id = ' . $mID . ' and is_delete != 0 AND is_visible != 0')
                ->orderBy('parent_id,sort_on asc')
                ->all();
        if ($returnFlag) {
            return count($data);
        } else {
            return $data;
        }
    }

}
