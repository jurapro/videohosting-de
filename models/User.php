<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $role
 *
 * @property Comment[] $comments
 * @property Video[] $videos
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public $password_repeat;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_repeat'], 'required'],
            [['username', 'email', 'password', 'password_repeat'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
        ];
    }

    /**В
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Повторение пароля',
            'role' => 'Роль',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_user' => 'id']);
    }

    /**
     * Gets query for [[Videos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['id_user' => 'id']);
    }

    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function isAdmin()
    {
        return $this->role === 1;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->password = md5($this->password);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


}
