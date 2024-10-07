<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "queue".
 *
 * @property int $id
 * @property string $token
 * @property int|null $role_id
 * @property int|null $room_id
 * @property int|null $user_id
 * @property string $date
 * @property string $time
 * @property int|null $priority_id Call after this queue ID
 * @property int|null $trail_id
 * @property int $status
 * @property string|null $call_time
 * @property int|null $recall_count
 * @property string|null $recall_time
 * @property string|null $end_time
 *
 * @property Queue[] $queues
 * @property Role $role
 * @property Room $room
 * @property Queue $trail
 * @property User $user
 */
class Queue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'date', 'time', 'status'], 'required'],
            [['role_id', 'room_id', 'user_id', 'priority_id', 'trail_id', 'status', 'recall_count'], 'integer'],
            [['date', 'time', 'call_time', 'recall_time', 'end_time'], 'safe'],
            [['token'], 'string', 'max' => 8],
            [['priority_id'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
            [['trail_id'], 'exist', 'skipOnError' => true, 'targetClass' => Queue::class, 'targetAttribute' => ['trail_id' => 'id']],
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
            'token' => 'Token',
            'role_id' => 'Role ID',
            'room_id' => 'Room ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'time' => 'Time',
            'priority_id' => 'Priority ID',
            'trail_id' => 'Trail ID',
            'status' => 'Status',
            'call_time' => 'Call Time',
            'recall_count' => 'Recall Count',
            'recall_time' => 'Recall Time',
            'end_time' => 'End Time',
        ];
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueues()
    {
        return $this->hasMany(Queue::class, ['trail_id' => 'id']);
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
     * Gets query for [[Trail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrail()
    {
        return $this->hasOne(Queue::class, ['id' => 'trail_id']);
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
