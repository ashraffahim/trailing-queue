<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "token_count".
 *
 * @property int $id
 * @property int $role_id
 * @property int $room_id
 * @property int $user_id
 * @property int $count
 * @property int|null $served
 * @property int|null $last_id
 * @property string $date
 *
 * @property Queue $last
 * @property Role $role
 * @property Room $room
 * @property User $user
 */
class TokenCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'room_id', 'user_id', 'count', 'date'], 'required'],
            [['role_id', 'room_id', 'user_id', 'count', 'served', 'last_id'], 'integer'],
            [['date'], 'safe'],
            [['last_id'], 'exist', 'skipOnError' => true, 'targetClass' => Queue::class, 'targetAttribute' => ['last_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'room_id' => 'Room ID',
            'user_id' => 'User ID',
            'count' => 'Count',
            'served' => 'Served',
            'last_id' => 'Last ID',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Last]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLast()
    {
        return $this->hasOne(Queue::class, ['id' => 'last_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
