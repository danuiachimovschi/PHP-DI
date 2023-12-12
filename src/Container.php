<?php

declare(strict_types=1);

namespace Danu\PhpDi;

use Danu\PhpDi\Contracts\ContainerContract;
use Danu\PhpDi\Exception\ContainerException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Container implements ContainerContract
{
    /**
     * @var array
     */
    private array $services = [];

    /**
     * The container's  instance.
     *
     * @var static
     */
    private static $instance;

    /**
     * @var string
     */
    private string $methodSeparator = '@';

    /**
     * @return static
     */
    public static function instance(): static
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $id
     * @return object
     */
    public function get(string $id): object
    {
        if(!isset($this->services[$id])) throw ContainerException::notFoundContainer($id);

        return $this->make($this->services[$id]);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @param $class
     * @param array $parameters
     * @return mixed
     */
    public function make($class, array $parameters = []): mixed
    {
        try {
            $classReflection = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw ContainerException::classDoesNotExist($class);
        }

        $constructorParams = $classReflection->getConstructor()->getParameters();

        $dependencies = $this->resolveParams($constructorParams, $parameters);

        return $classReflection->newInstance(...$dependencies);
    }

    /**
     * @param class-string $callable
     * @param array $parameters
     * @return mixed
     */
    public function call(string $callable, array $parameters = []): mixed
    {
        [$callbackClass, $method] = $this->resolveCallBack($callable);

        $methodReflection = new \ReflectionMethod($callbackClass, $method);

        $methodParams = $methodReflection->getParameters();

        $dependencies = $this->resolveParams($methodParams, $parameters);

        $class = $this->make($callbackClass);

        return $methodReflection->invoke($class, ...$dependencies);
    }

    /**
     * @param $callable
     * @return array
     */
    private function resolveCallBack($callable): array
    {
        $params = explode($this->methodSeparator, $callable);

        if(!isset($params[1])) $params[1] = '__invoke';

        return $params;
    }

    /**
     * @param array $methodParams
     * @param array $parameters
     * @return array
     * @throws \Exception
     */
    private function resolveParams(array $methodParams,array $parameters = []): array
    {
        $dependencies = [];

        foreach ($methodParams as $methodParam) {
            $paramClass = $methodParam->getType();

            if (!$paramClass->isBuiltin() && $paramClass instanceof ReflectionNamedType) {
                $className = $methodParam->getType()->getName();;
                $dependencies[] =  new $className();
            } else {
                $parameterName = $methodParam->getName();
                if(array_key_exists($parameterName, $parameters)) {
                    $dependencies[] = $parameters[$parameterName];
                } else {
                    if(!$methodParam->isOptional()) {
                        throw new \Exception("Cannot resolve class dependency {$methodParam->getName()}");
                    }
                }
            }
        }

        return $dependencies;
    }

    private function __construct()
    {
    }

    private function __clone(): void
    {
    }

    private function __wakeup()
    {
    }
}
