<?php

namespace app\models\databaseObjects;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $nid
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $auth_key
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nid', 'username', 'email', 'password_hash'], 'required'],
            [['nid'], 'string', 'max' => 21],
            [['username'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 200],
            [['password_hash'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 80],
            [['auth_key'], 'string', 'max' => 50],
            [['nid'], 'unique'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['username'], 'unique'],
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
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'auth_key' => 'Auth Key',
        ];
    }
}
