<?php

namespace App\Components;

use ReflectionClass;
use ReflectionProperty;

/**
 * Абстрактный класс для удобства работы с объектами передачи данных
 *
 * @author Oleg Pyatin
 */
abstract class BaseDto
{
    /**
     * Загружаем данные в DTO объект (во все подходящие публичные, защищенные и приватные
     *     свойства (не статические))
     *
     * @param array $array   Массив где содержатся значения для полей (соответствуя названиям)
     * @return self   Полученный после заполнения объект
     */
    public static function loadFromArray(array $array): self
    {
        $object = new static();
        $class = new ReflectionClass($object);

        foreach ($class->getProperties(~ReflectionProperty::IS_STATIC) as $field) {

            $field_name = $field->getName();

            if ($field->isPublic()) {

                $object->$field_name = $array[$field_name] ?? null;

            } else {

                // Для protected и private свойств заполняем значения через использование сеттеров

                $methods = $class->getMethods();

                foreach ($methods as $method) {
                    $parameters = $method->getParameters();

                    if ((substr($method->getName(), 0, 3)==="set") &&
                            $parameters[0]->name===$field_name && (count($parameters)===1)) {

                        $method->invokeArgs($object, [$field_name=>$array[$field_name]]);
                    }
                }
            }
        }

        return $object;
    }
}
