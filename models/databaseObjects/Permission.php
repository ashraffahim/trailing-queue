<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "permission".
 *
 * @property int $id
 * @property string $name
 *
 * @property UserPermission[] $userPermissions
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[UserPermissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserPermissions()
    {
        return $this->hasMany(UserPermission::class, ['permission_id' => 'id']);
    }
}
