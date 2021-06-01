<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_video
 * @property string $date
 * @property string $text
 *
 * @property User $user
 * @property Video $video
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_video', 'text'], 'required'],
            [['id_video'], 'integer'],
            [['id_user'], 'default', 'value' => Yii::$app->user->getId()],
            [['text'], 'string'],
            [['id_user'], 'exist', 'skipOnError' => true,
                'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_video'], 'exist', 'skipOnError' => true,
                'targetClass' => Video::className(), 'targetAttribute' => ['id_video' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_video' => 'Id Video',
            'date' => 'Date',
            'text' => 'Text',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * Gets query for [[Video]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'id_video']);
    }
}
