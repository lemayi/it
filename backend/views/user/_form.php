<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Role;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role_id')->dropDownList(Role::getRoleList()) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'mobile_bind')->dropDownList($model->getBindList()) ?>

    <?= $form->field($model, 'email_bind')->dropDownList($model->getBindList()) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
