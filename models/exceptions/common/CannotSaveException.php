<?php

namespace app\models\exceptions\common;

use app\models\exceptions\NotHumanException;
use ReflectionClass;
use Throwable;
use yii\db\ActiveRecord;

class CannotSaveException extends NotHumanException {
    public function __construct(ActiveRecord $model, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        \Yii::warning($model->toArray(), (new ReflectionClass(self::class))->getShortName() . ' tried to save');
        \Yii::warning($model->errors, (new ReflectionClass(self::class))->getShortName() . ' errors');
    }
}

?>