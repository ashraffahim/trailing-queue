<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string|null $email
 * @property string $password_hash
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int $room_id
 * @property int $role_id
 * @property bool|null $is_open
 * @property bool|null $is_active
 * @property string|null $auth_key
 *
 * @property Queue[] $queues
 * @property Role $role
 * @property Room $room
 * @property UserTokenCount[] $tokenCounts
 */
class User extends \yii\db\ActiveRecord
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
    public function rules()
    {
        return [
            [['username', 'room_id', 'role_id'], 'required'],
            [['room_id', 'role_id'], 'integer'],
            [['is_open', 'is_active'], 'boolean'],
            [['username'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 200],
            [['password_hash'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 50],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
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
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'room_id' => 'Room ID',
            'role_id' => 'Role ID',
            'is_open' => 'Is Open',
            'is_active' => 'Is Active',
            'auth_key' => 'Auth Key',
        ];
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueues()
    {
        return $this->hasMany(Queue::class, ['user_id' => 'id']);
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
     * Gets query for [[UserTokenCounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokenCounts()
    {
        return $this->hasMany(UserTokenCount::class, ['user_id' => 'id']);
    }
}
