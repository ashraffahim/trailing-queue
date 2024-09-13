<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string $nid
 * @property string $name
 * @property int $owner_user_id
 *
 * @property User $ownerUser
 * @property Turf[] $turves
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nid', 'name', 'owner_user_id'], 'required'],
            [['owner_user_id'], 'default', 'value' => null],
            [['owner_user_id'], 'integer'],
            [['nid'], 'string', 'max' => 21],
            [['name'], 'string', 'max' => 32],
            [['nid'], 'unique'],
            [['owner_user_id'], 'unique'],
            [['owner_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nid' => 'Nid',
            'name' => 'Name',
            'owner_user_id' => 'Owner User ID',
        ];
    }

    /**
     * Gets query for [[OwnerUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwnerUser()
    {
        return $this->hasOne(User::class, ['id' => 'owner_user_id']);
    }

    /**
     * Gets query for [[Turves]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurves()
    {
        return $this->hasMany(Turf::class, ['account_id' => 'id']);
    }
}
