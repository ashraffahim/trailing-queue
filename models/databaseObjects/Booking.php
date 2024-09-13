<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "booking".
 *
 * @property int $id
 * @property string $nid
 * @property int $turf_id
 * @property int|null $user_id
 * @property string $date
 * @property string|null $email
 * @property float|null $phone
 * @property string $start_time
 * @property string $end_time
 *
 * @property Turf $turf
 * @property User $user
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nid', 'turf_id', 'date', 'start_time', 'end_time'], 'required'],
            [['turf_id', 'user_id'], 'default', 'value' => null],
            [['turf_id', 'user_id'], 'integer'],
            [['date', 'start_time', 'end_time'], 'safe'],
            [['phone'], 'number'],
            [['nid'], 'string', 'max' => 21],
            [['email'], 'string', 'max' => 64],
            [['email'], 'email'],
            [['nid'], 'unique'],
            [['turf_id'], 'exist', 'skipOnError' => true, 'targetClass' => Turf::class, 'targetAttribute' => ['turf_id' => 'id']],
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
            'nid' => 'Nid',
            'turf_id' => 'Turf ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'email' => 'Email',
            'phone' => 'Phone',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }

    /**
     * Gets query for [[Turf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurf()
    {
        return $this->hasOne(Turf::class, ['id' => 'turf_id']);
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
