<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int $floor
 * @property string $room
 * @property int $role_id
 * @property bool|null $is_open
 * @property string|null $auth_key
 *
 * @property Queue[] $queues
 * @property Role $role
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
            [['username', 'email', 'password_hash', 'floor', 'room', 'role_id'], 'required'],
            [['floor', 'role_id'], 'integer'],
            [['is_open'], 'boolean'],
            [['username'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 200],
            [['password_hash'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 20],
            [['room'], 'string', 'max' => 5],
            [['auth_key'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
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
            'password_hash' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'floor' => 'Floor',
            'room' => 'Room',
            'role_id' => 'Role ID',
            'is_open' => 'Is Open',
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
}
