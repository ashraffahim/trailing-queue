<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property int $id
 * @property string $name
 * @property int $floor
 *
 * @property UserTokenCount[] $tokenCounts
 * @property User[] $users
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'floor'], 'required'],
            [['floor'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'floor' => 'Floor',
        ];
    }

    /**
     * Gets query for [[UserTokenCounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokenCounts()
    {
        return $this->hasMany(UserTokenCount::class, ['room_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['room_id' => 'id']);
    }
}
