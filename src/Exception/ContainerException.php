<?php

namespace Danu\PhpDi\Exception;

class ContainerException extends InternalException
{
    /**
     * @param string $id
     * @return self
     */
    public static function notFoundContainer(string $id): ContainerException
    {
        return new self("No entry was found for **this** identifier: {$id}");
    }

    public static function classDoesNotExist(string $className): ContainerException
    {
        return new self("Class does not exist: {$className}");
    }
}