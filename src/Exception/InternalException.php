<?php

declare(strict_types=1);

namespace Danu\PhpDi\Exception;

class InternalException extends \Exception
{
    /**
     * @param string $message
     * @return static
     */
    public static function internalException(string $message): InternalException
    {
        return new self($message);
    }
}