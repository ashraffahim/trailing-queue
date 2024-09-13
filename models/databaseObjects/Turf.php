<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "turf".
 *
 * @property int $id
 * @property string $nid
 * @property string|null $name
 * @property string $address
 * @property int $account_id
 *
 * @property Account $account
 * @property Booking[] $bookings
 * @property Slot[] $slots
 */
class Turf extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'turf';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nid', 'address', 'account_id'], 'required'],
            [['account_id'], 'default', 'value' => null],
            [['account_id'], 'integer'],
            [['nid'], 'string', 'max' => 21],
            [['name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [['nid'], 'unique'],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::class, 'targetAttribute' => ['account_id' => 'id']],
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
            'address' => 'Address',
            'account_id' => 'Account ID',
        ];
    }

    /**
     * Gets query for [[Account]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['turf_id' => 'id']);
    }

    /**
     * Gets query for [[Slots]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlots()
    {
        return $this->hasMany(Slot::class, ['turf_id' => 'id']);
    }
}
