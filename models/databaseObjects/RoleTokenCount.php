<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "role_token_count".
 *
 * @property int $id
 * @property int $role_id
 * @property int $room_id
 * @property int $count
 * @property int|null $priority_count
 * @property int|null $last_id
 * @property string $date
 *
 * @property Role $role
 * @property Room $room
 */
class RoleTokenCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_token_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'room_id', 'count', 'date'], 'required'],
            [['role_id', 'room_id', 'count', 'priority_count', 'last_id'], 'integer'],
            [['date'], 'safe'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['room_id' => 'id']],
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
            'count' => 'Count',
            'priority_count' => 'Priority Count',
            'last_id' => 'Last ID',
            'date' => 'Date',
        ];
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
}
