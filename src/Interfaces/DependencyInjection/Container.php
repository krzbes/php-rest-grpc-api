<?php

namespace App\Interfaces\DependencyInjection;

use ReflectionClass;

class Container
{
    private array $instances = [];

    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!class_exists($id)) {
            throw new Exception("Class $id doesn't exist");
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
                    throw new Exception("Failed to autowire \${$param->getName()} w class $id");
                }


                $dependencies[] = $this->get($paramType->getName());
            }


            $instance = $reflectionClass->newInstanceArgs($dependencies);
        }


        $this->instances[$id] = $instance;

        return $instance;
    }
}