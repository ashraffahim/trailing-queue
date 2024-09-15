<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "queue".
 *
 * @property int $id
 * @property string $token
 * @property int|null $user_id
 * @property string $date
 * @property string $time
 * @property int|null $trail_id
 * @property int $status
 * @property string|null $call_time
 * @property int|null $recall_count
 * @property string|null $recall_time
 * @property string|null $end_time
 *
 * @property Queue[] $queues
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
            [['user_id', 'trail_id', 'status', 'recall_count'], 'integer'],
            [['date', 'time', 'call_time', 'recall_time', 'end_time'], 'safe'],
            [['token'], 'string', 'max' => 8],
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
            'user_id' => 'User ID',
            'date' => 'Date',
            'time' => 'Time',
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
