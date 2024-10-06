<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name
 * @property string|null $token_prefix
 * @property int $task
 * @property bool|null $is_open
 * @property bool|null $is_kiosk_visible
 *
 * @property Queue[] $queues
 * @property UserTokenCount[] $userTokenCounts
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'task'], 'required'],
            [['task'], 'integer'],
            [['is_open', 'is_kiosk_visible'], 'boolean'],
            [['name'], 'string', 'max' => 20],
            [['token_prefix'], 'string', 'max' => 3],
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
            'token_prefix' => 'Token Prefix',
            'task' => 'Task',
            'is_open' => 'Is Open',
            'is_kiosk_visible' => 'Is Kiosk Visible',
        ];
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueues()
    {
        return $this->hasMany(Queue::class, ['role_id' => 'id']);
    }

    /**
     * Gets query for [[UserTokenCounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokenCounts()
    {
        return $this->hasMany(UserTokenCount::class, ['role_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
    }
}
