<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name
 * @property string|null $token_prefix
 * @property bool|null $is_open
 * @property bool|null $is_kiosk_visible
 *
 * @property RolePermission[] $rolePermissions
 * @property RoleTokenCount[] $roleTokenCounts
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
            [['name'], 'required'],
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
            'is_open' => 'Is Open',
            'is_kiosk_visible' => 'Is Kiosk Visible',
        ];
    }

    /**
     * Gets query for [[RolePermissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['role_id' => 'id']);
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
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
    }
}
