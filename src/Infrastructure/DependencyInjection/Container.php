<?php

namespace App\Infrastructure\DependencyInjection;

use ReflectionClass;
use RuntimeException;

class Container
{
    private array $instances = [];
    private array $bindings = [];

    private array $factories = [];

    public function bindFactory(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }
    public function get(string $id)
    {
        if (isset($this->bindings[$id])) {
            $id = $this->bindings[$id];
        }

        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->factories[$id])) {
            $this->instances[$id] = ($this->factories[$id])($this);
            return $this->instances[$id];
        }

        if (!class_exists($id)) {
            throw new RuntimeException("Class $id doesn't exist");
        }


        $reflectionClass = new ReflectionClass($id);
        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            $instance = new $id();
        } else {

            $params = $constructor->getParameters();
            $dependencies = [];

            foreach ($params as $param) {
                $paramType = $param->getType();

                if (!$paramType || $paramType->isBuiltin()) {
                    throw new RuntimeException("Failed to autowire \${$param->getName()} w class $id");
                }


                $dependencies[] = $this->get($paramType->getName());
            }


            $instance = $reflectionClass->newInstanceArgs($dependencies);
        }


        $this->instances[$id] = $instance;

        return $instance;
    }

    public function set(string $id, object $instance): void
    {
        $this->instances[$id] = $instance;
    }

    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }
}