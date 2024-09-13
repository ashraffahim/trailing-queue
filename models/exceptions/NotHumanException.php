<?php

namespace app\models\exceptions;

use Throwable;

class NotHumanException extends \Exception implements NotHumanExceptionInterface {
    function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        \Yii::warning($this, (new \ReflectionClass(static::class))->getShortName() . ' instanciated');
    }
}

?>