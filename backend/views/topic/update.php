<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Topic */

$this->title = '编辑频道: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '频道', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="topic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
