<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_category
 * @property string $title
 * @property string|null $description
 * @property string $file
 * @property string $date
 * @property int $amount_of_likes
 * @property int $amount_of_dislikes
 * @property int $restrictions
 *
 * @property Comment[] $comments
 * @property User $user
 * @property Category $category
 */
class Video extends \yii\db\ActiveRecord
{
    public $uploadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_category', 'title'], 'required'],
            [['id_category','restrictions'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['id_user'], 'default', 'value' => Yii::$app->user->getId()],
            [['id_user'], 'exist', 'skipOnError' => true,
                'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_category'], 'exist', 'skipOnError' => true,
                'targetClass' => Category::className(), 'targetAttribute' => ['id_category' => 'id']],
            [['uploadFile'], 'file', 'skipOnEmpty' => false,
                'extensions' => ['mp4']],
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
            'id_category' => 'Категория',
            'title' => 'Название',
            'description' => 'Описание',
            'file' => 'Видео',
            'date' => 'Дата загрузки',
            'amount_of_likes' => 'Количество лайков',
            'amount_of_dislikes' => 'Количество дизлайков',
            'restrictions' => 'Ограничения',
        ];
    }

    public function getRestrictionText()
    {
        switch ($this->restrictions) {
            case 0:
                return 'Нет ограничений';
            case 1:
                return 'Нарушение';
            case 2:
                return 'Теневой бан';
            case 3:
                return 'Бан';
        }
        return 'Не определено';
    }

    public function clearRestriction()
    {
        $this->restrictions = 0;
        $this->save(false);
    }

    public function setViolation()
    {
        $this->restrictions = 1;
        $this->save(false);
    }

    public function setShadowBan()
    {
        $this->restrictions = 2;
        $this->save(false);
    }

    public function setBan()
    {
        $this->restrictions = 3;
        $this->save(false);
    }

    public function upload()
    {
        if ($this->validate()) {
            $file_name = 'uploads/' . $this->uploadFile->baseName . '.' . $this->uploadFile->extension;
            $this->uploadFile->saveAs($file_name);
            $this->file = '/' . $file_name;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_video' => 'id']);
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
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_category']);
    }


}
