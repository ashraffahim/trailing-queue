<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "role_permission".
 *
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 *
 * @property Permission $permission
 * @property Role $role
 */
class RolePermission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_permission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permission_id', 'role_id'], 'required'],
            [['permission_id', 'role_id'], 'integer'],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Permission::class, 'targetAttribute' => ['permission_id' => 'id']],
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
            'permission_id' => 'Permission ID',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Permission]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermission()
    {
        return $this->hasOne(Permission::class, ['id' => 'permission_id']);
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
