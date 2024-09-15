<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "user_token_count".
 *
 * @property int $id
 * @property int $user_id
 * @property int $count
 * @property int|null $served
 * @property int|null $last_id
 * @property string $date
 *
 * @property User $user
 */
class UserTokenCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_token_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'count', 'date'], 'required'],
            [['user_id', 'count', 'served', 'last_id'], 'integer'],
            [['date'], 'safe'],
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
            'user_id' => 'User ID',
            'count' => 'Count',
            'served' => 'Served',
            'last_id' => 'Last ID',
            'date' => 'Date',
        ];
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
