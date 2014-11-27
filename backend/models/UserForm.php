<?php
namespace backend\models;

use Yii;
use common\models\User;
use yii\db\ActiveRecord;
use backend\models\Role;
use yii\web\NotFoundHttpException;

/**
 * User form
 */
class UserForm extends ActiveRecord
{
    public $role_id;
    public $username;
    public $password;
    public $email;
    public $mobile;
    public $mobile_bind;
    public $email_bind;
    public $status;

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['role_id', 'required'],
            ['role_id', 'integer'],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '会员名称已经被使用'],
            ['username', 'string', 'length' => [4,50]],

            ['password', 'string', 'length' => [6,30]],
            ['password', 'required', 'on' => 'create'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '电子邮箱已经被使用'],

            ['mobile', 'filter', 'filter' => 'trim'],
            ['mobile', 'integer'],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => '手机号已经被使用'],

            ['mobile_bind', 'required'],
            ['mobile_bind', 'default', 'value' => User::BIND_NO],
            ['mobile_bind', 'in', 'range' => [User::BIND_NO, User::BIND_YES]],

            ['email_bind', 'required'],
            ['email_bind', 'default', 'value' => User::BIND_NO],
            ['email_bind', 'in', 'range' => [User::BIND_NO, User::BIND_YES]],

            ['status', 'required'],
            ['status', 'default', 'value' => User::STATUS_ENABLE],
            ['status', 'in', 'range' => [User::STATUS_ENABLE, User::STATUS_DISABLE]],
        ];
    }

    // scenarios
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['role_id','username','password_hash','email','mobile','email_bind','mobile_bind','status'];
        $scenarios['update'] = ['password_hash'];
        return $scenarios;
    }

    // status array
    public function getStatusList()
    {
        return [
            User::STATUS_ENABLE    => '启用',
            User::STATUS_DISABLE   => '禁用'
        ];
    }

    // status array
    public function getBindList()
    {
        return [
            User::BIND_NO    => '否',
            User::BIND_YES   => '是'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->role_id      = $this->role_id;
            $user->username     = $this->username;
            $user->email        = $this->email;
            $user->mobile       = $this->mobile;
            $user->email_bind   = $this->email_bind;
            $user->mobile_bind  = $this->mobile_bind;
            $user->status       = $this->status;

            if(!empty($this->password)){
                $user->setPassword($this->password);
            }
            
            $user->generateAuthKey();
            $user->save();
            return $user;
        }

        return null;
    }

    // // relation role table
    // public function getRole()
    // {
    //     if (($model = Role::findOne($this->role_id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    // }
}
