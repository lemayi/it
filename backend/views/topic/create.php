<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Topic */

$this->title = '新增频道';
$this->params['breadcrumbs'][] = ['label' => '频道', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
