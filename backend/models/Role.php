<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Role extends ActiveRecord
{
    const STATUS_ENABLE      = 1;
    const STATUS_DISABLE     = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
            [['status'], 'default', 'value' => self::STATUS_ENABLE],
            [['status'], 'in', 'range' => [self::STATUS_ENABLE, self::STATUS_DISABLE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '会员组名称',
            'status' => '状态',
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

    // role list
    public static function getRoleList()
    {
        $data = self::find()->select(['id', 'name'])
                            ->where(['status' => 1])
                            ->orderBy('id DESC')
                            ->asArray()
                            ->all();

        foreach($data as $val){
            $array[$val['id']] = $val['name'];
        }

        return $array;
    }
}
