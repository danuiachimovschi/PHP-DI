<?php

namespace Danu\PhpDi\Exception;

class ContainerException extends InternalException
{
    /**
     * @param string $id
     * @return static
     */
    public static function notFoundContainer(string $id): static
    {
        return self::make("No entry was found for **this** identifier: {$id}");
    }

    public static function classDoesNotExist(string $className): static
    {
        return self::make("Class does not exist: {$className}");
    }
}