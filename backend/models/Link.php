<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property integer $id
 * @property integer $topic_id
 * @property string $title
 * @property integer $type_id
 * @property string $url
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id', 'type_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'url', 'created_at', 'updated_at'], 'required'],
            [['title'], 'string', 'max' => 400],
            [['url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topic_id' => 'Topic ID',
            'title' => 'Title',
            'type_id' => 'Type ID',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
