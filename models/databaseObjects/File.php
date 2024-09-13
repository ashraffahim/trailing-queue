<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $uuid
 * @property string|null $name
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['uuid'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'name' => 'Name',
        ];
    }
}
