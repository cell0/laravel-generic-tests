<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 31/10/2018
 * Time: 17:26
 */

namespace Cell0\LGT\ResourceTest;


use Throwable;

class MinimalRequirementException extends \Exception
{
    public function __construct(string $message = "you did not set the modelClass, resourceClass or attributes", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}