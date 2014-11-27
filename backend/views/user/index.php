<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Role;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增会员', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            [
                'attribute' => 'role_id',
                'value' => function($model){ return isset($model->role->name) ? $model->role->name : ''; },
                'filter'=> Role::getRoleList()
            ],
            'username',
            'email:email',
            'mobile',
            // 'mobile_bind',
            // 'email_bind:email',
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
            // 'login_count',
            // 'last_login_ip',
            // 'last_login_time',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '7%'],
            ],
        ],
    ]); ?>

</div>
