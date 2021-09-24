<?php

namespace App\Components;

use ReflectionClass;
use ReflectionProperty;

abstract class AbstractDto
{
    public static function populateByArray(array $data): self
    {
        $dto = new static();

        foreach ($dto->attributes() as $attribute) {
            $dto->$attribute = $data[$attribute] ?? null;
        }

        return $dto;
    }

    public function attributes($excludeSetters = false): array
    {
        $class = new ReflectionClass($this);
        $attributes = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $attributes[] = $property->getName();
            }
        }

        if (!$excludeSetters) {
            foreach ($class->getProperties(ReflectionProperty::IS_PROTECTED) as $property) {
                if (!$property->isStatic()) {
                    $name = substr($property->getName(), 1);

                    if ($class->hasMethod('set' . $name)) {
                        $attributes[] = $name;
                    }
                }
            }
        }

        return $attributes;
    }
}
