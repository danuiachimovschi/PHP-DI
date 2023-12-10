<?php

namespace Danu\PhpDi\Exception;

class ContainerException extends InternalException
{
    /**
     * @param string $id
     * @return static
     */
    public static function NotFoundContainer(string $id): static
    {
        return self::make("No entry was found for **this** identifier: {$id}");
    }
}