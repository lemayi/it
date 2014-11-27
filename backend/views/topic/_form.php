<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Topic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($model->getTopicList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>

    <?= $form->field($model, 'sort')->textInput(['value' => 0, 'maxlength' => 11]) ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'seo_keyword')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'seo_desc')->textarea(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
