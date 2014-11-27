<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员组';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">
    <p>
        <?= Html::a('新增会员组', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
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
                'attribute'=>'created_at', 
                'format'=>['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute'=>'updated_at', 
                'format'=>['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '7%'],
            ],
        ],
    ]); ?>

</div>
