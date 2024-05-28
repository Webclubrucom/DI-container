<?php

declare(strict_types = 1);

namespace system\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use system\DI\Exception\ContainerException;

class Container implements ContainerInterface
{
    private array $entries = [];

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */

    public function get(string $id, array $parameters = []): object
    {
        foreach ($parameters as $interface => $implementation) {
            $this->entries[$interface] = $implementation;
        }

        if ($this->has($id)) {
            $entry = $this->entries[$id];

            if (is_callable($entry)) {
                return $entry($this);
            }

            $id = $entry;
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function resolve(string $id):mixed
    {
        // 1. Проверяем наличие класса, который мы пытаемся добавить в контейнер
        try {
            $reflectionClass = new ReflectionClass($id);
        } catch(ReflectionException $e) {
            throw new ContainerException($e->getMessage(), $e->getCode(), $e);
        }

        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException('Класс "' . $id . '" не может быть создан в виде зависимости');
        }

        // 2. Проверяем, имеется ли у класса конструктор
        $constructor = $reflectionClass->getConstructor();

        if (! $constructor) {
            return new $id;
        }

        // 3. Проверяем наличие зависимостей в конструкторе
        $parameters = $constructor->getParameters();

        if (! $parameters) {
            return new $id;
        }

        // 4. Если зависимостью конструктора является класс, то добавляем этот класс в контейнер
        $dependencies = array_map(

        /**
         * @throws ContainerException|ReflectionException
         */
            function (ReflectionParameter $param) use ($id) {
                $name = $param->getName();
                $type = $param->getType();

                if (! $type) {
                    throw new ContainerException(
                        'Не удалось добавить класс "' . $id . '" в виде зависимости, потому что у параметра "' . $name . '" отсутствует подсказка по типу'
                    );
                }

                if ($type instanceof ReflectionUnionType) {
                    throw new ContainerException(
                        'Не удалось добавить класс "' . $id . '" в виде зависимости, потому что параметр "' . $name . '" не является классом'
                    );
                }

                if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                    return $this->get($type->getName());
                }

                throw new ContainerException(
                    'Не удалось добавить класс "' . $id . '" в виде зависимости из-за недопустимого параметра "' . $name . '"'
                );
            },
            $parameters
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}