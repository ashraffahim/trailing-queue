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
 * @property int|null $priority_for_id
 * @property bool|null $is_open
 * @property bool|null $is_kiosk_visible
 *
 * @property Role $priorityFor
 * @property Queue[] $queues
 * @property Role $role
 * @property RoleTokenCount[] $roleTokenCounts
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
            [['task', 'priority_for_id'], 'integer'],
            [['is_open', 'is_kiosk_visible'], 'boolean'],
            [['name'], 'string', 'max' => 20],
            [['token_prefix'], 'string', 'max' => 3],
            [['priority_for_id'], 'unique'],
            [['priority_for_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['priority_for_id' => 'id']],
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
            'priority_for_id' => 'Priority For ID',
            'is_open' => 'Is Open',
            'is_kiosk_visible' => 'Is Kiosk Visible',
        ];
    }

    /**
     * Gets query for [[PriorityFor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPriorityFor()
    {
        return $this->hasOne(Role::class, ['id' => 'priority_for_id']);
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
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['priority_for_id' => 'id']);
    }

    /**
     * Gets query for [[RoleTokenCounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoleTokenCounts()
    {
        return $this->hasMany(RoleTokenCount::class, ['role_id' => 'id']);
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
