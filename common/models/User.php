<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\models\Role;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $mobile
 * @property integer $mobile_bind
 * @property integer $email_bind
 * @property integer $role_id
 * @property integer $status
 * @property integer $login_count
 * @property string $last_login_ip
 * @property string $last_login_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_ENABLE      = 1;
    const STATUS_DISABLE     = 2;
    const BIND_YES           = 1;
    const BIND_NO            = 0;

    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
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
            ['username', 'string', 'length' => [2,50]],

            ['password', 'string', 'length' => [6,50]],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '密码',
            'password' => '密码',
            'password_hash' => '密码',
            'password_reset_token' => '密码重设Token',
            'email' => '邮箱',
            'mobile' => '手机号',
            'mobile_bind' => '是否绑定手机号',
            'email_bind' => '是否绑定Email',
            'role_id' => '会员组',
            'status' => '状态',
            'login_count' => '登录次数',
            'last_login_ip' => '最后登录IP',
            'last_login_time' => '最后登录时间',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
        ];
    }


    // scenarios
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['role_id','username','password','email','mobile','email_bind','mobile_bind','status'];
        $scenarios['update'] = ['role_id','username','password','email','mobile','email_bind','mobile_bind','status'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ENABLE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $isAdmin=false)
    {
        if($isAdmin){
            return static::findOne(['username' => $username, 'status' => self::STATUS_ENABLE, 'role_id' => 1]);
        }
        return static::findOne(['username' => $username, 'status' => self::STATUS_ENABLE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ENABLE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    // relation role table
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    // status array
    public function getStatusList()
    {
        return [
            self::STATUS_ENABLE    => '启用',
            self::STATUS_DISABLE   => '禁用'
        ];
    }

    // status array
    public function getBindList()
    {
        return [
            self::BIND_NO    => '否',
            self::BIND_YES   => '是'
        ];
    }

    public function signup()
    {
        if ($this->validate()) {
            $this->setPassword($this->password_hash);
            $this->generateAuthKey();
            $rs = $this->save();

            return $rs;
        }

        return null;
    }
}
