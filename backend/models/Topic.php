<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "topic".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $slug
 * @property string $title
 * @property integer $status
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 * @property integer $created_at
 * @property integer $updated_at
 */
class Topic extends \yii\db\ActiveRecord
{
    const STATUS_ENABLE      = 1;
    const STATUS_DISABLE     = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['parent_id', 'required'],
            ['parent_id', 'integer'],

            ['title', 'required'],
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'string', 'length' => [2,50]],
            ['title', 'unique'],


            ['slug', 'required'],
            ['slug', 'string', 'length' => [1,20]],
            ['slug', 'filter', 'filter' => 'trim'],
            ['slug', 'unique'],

            ['status', 'required'],
            ['status', 'default', 'value' => self::STATUS_ENABLE],
            ['status', 'in', 'range' => [self::STATUS_ENABLE, self::STATUS_DISABLE]],

            ['sort', 'required'],
            ['sort', 'integer'],

            ['seo_title', 'filter', 'filter' => 'trim'],

            ['seo_keyword', 'filter', 'filter' => 'trim'],

            ['seo_desc', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '上级频道',
            'slug' => '链接地址',
            'title' => '频道名称',
            'status' => '状态',
            'sort' => '排序',
            'seo_title' => 'Seo Title',
            'seo_keyword' => 'Seo Keyword',
            'seo_desc' => 'Seo Desc',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    // status array
    public function getStatusList()
    {
        return [
            self::STATUS_ENABLE    => '启用',
            self::STATUS_DISABLE   => '禁用'
        ];
    }

    // get the topic list
    public function getTopicList($id=0)
    {
        $data = self::find()->select(['id', 'title'])
                            ->where(['status' => 1])
                            ->orderBy('sort DESC')
                            ->asArray()
                            ->all();
        
        $array[0] = '一级频道';
        foreach($data as $val){
            $array[$val['id']] = $val['title'];
        }

        return $array;
    }

    // get topic name
    public static function getTopicName($id)
    {
        if(0 == $id) return '一级频道';
        if (($model = self::findOne($id)) !== null) {
            return $model->title;
        } else {
            return '';
        }
    }
}
