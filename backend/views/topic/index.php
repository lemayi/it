<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TopicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Topics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-index">

    <p>
        <?= Html::a('新增频道', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'parent_id',
                'format' => 'html',
                'value'=>function ($model) {
                            if(0 == $model->parent_id) {
                                return '一级频道';
                            }else {
                                return Html::a($model->getTopicName($model->parent_id), ['update', 'id' => $model->parent_id]);
                            }
                        },
            ],
            [
                'attribute' => 'title',
                'format' => 'html',
                'value'=>function ($model) {
                            return Html::a($model->getTopicName($model->id), ['update', 'id' => $model->id]);
                        },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value'=>function ($model) {
                            if(1 == $model->status){
                                return Html::tag('span', ' ', ['class' => 'label label-success glyphicon glyphicon-ok']);
                                // return '<span class="glyphicon glyphicon-ok"></span>';
                            }else if(2 == $model->status){
                                return Html::tag('span', ' ', ['class' => 'label label-danger glyphicon glyphicon-remove']);
                            }
                        },
                'filter'=> ['1' => '启用', '2' => '禁用'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '7%'],
            ],
        ],
    ]); ?>

</div>
