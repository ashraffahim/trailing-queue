<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "slot".
 *
 * @property int $id
 * @property string $nid
 * @property int $turf_id
 * @property int $day
 * @property string $start_time
 * @property string $end_time
 * @property int $is_open
 *
 * @property Turf $turf
 */
class Slot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nid', 'turf_id', 'day', 'start_time', 'end_time', 'is_open'], 'required'],
            [['turf_id', 'day', 'is_open'], 'default', 'value' => null],
            [['turf_id', 'day', 'is_open'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['nid'], 'string', 'max' => 21],
            [['nid'], 'unique'],
            [['turf_id'], 'exist', 'skipOnError' => true, 'targetClass' => Turf::class, 'targetAttribute' => ['turf_id' => 'id']],
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
            'day' => 'Day',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'is_open' => 'Is Open',
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
}
