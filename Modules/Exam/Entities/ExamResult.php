<?php

namespace Modules\Exam\Entities;

class ExamResult
{
    public $type;
    public $degree_amount;
    public $process;

    public const STANDART_THREE_DEGREES = "3";
    public const STANDART_THREE_LEVEL_ONE = 1;
    public const STANDART_THREE_LEVEL_TWO = 2;
    public const STANDART_THREE_LEVEL_THREE = 3;

    public const STANDART_FOUR_DEGREES = "4";
    public const STANDART_FOUR_LEVEL_ONE = 1;
    public const STANDART_FOUR_LEVEL_TWO = 2;
    public const STANDART_FOUR_LEVEL_THREE = 3;
    public const STANDART_FOUR_LEVEL_FOUR = 4;

    public const STANDART_FIVE_DEGREES = "5";
    public const STANDART_FIVE_LEVEL_ONE = 1;
    public const STANDART_FIVE_LEVEL_TWO = 2;
    public const STANDART_FIVE_LEVEL_THREE = 3;
    public const STANDART_FIVE_LEVEL_FOUR = 4;
    public const STANDART_FIVE_LEVEL_FIVE = 5;

    public const STANDART_RESULT_DISPLAY = "standart";
    public const CUSTOM_RESULT_DISPLAY = "custom";

    public const STRICT_PROCESS_TYPE = "strict";
    public const HARD_PROCESS_TYPE = "hard";

    public const ANSWERS_NO_SHOW = "no-show";
    public const ANSWERS_SIMPLE_SHOW = "simple-show";
    public const ANSWERS_RIGHT_MARK_SHOW = "right-mark";
    public const ANSWERS_WRONG_WITH_RIGHT = "wrong-with-right";


    public static function getStandartThreeLevelMarkMessage(int $result_level): string
    {
        switch ($result_level) {
            case static::STANDART_THREE_LEVEL_ONE:
                return "Низкий уровень знаний";
            case static::STANDART_THREE_LEVEL_TWO:
                return "Средний уровень знаний";
            case static::STANDART_THREE_LEVEL_THREE:
                return "Высокий уровень знаний";
            default:
                return "";
        }
    }

    public static function getStandartFourLevelMarkMessage(int $result_level): string
    {
        switch ($result_level) {
            case static::STANDART_FOUR_LEVEL_ONE:
                return "Низкий уровень знаний";
            case static::STANDART_FOUR_LEVEL_TWO:
                return "Средний уровень знаний";
            case static::STANDART_FOUR_LEVEL_THREE:
                return "Высокий уровень знаний";
            case static::STANDART_FOUR_LEVEL_THREE:
                return "Очень высокий уровень знаний";
            default:
                return "";
        }
    }

    public static function getStandartFiveLevelMarkMessage(int $result_level): string
    {
        switch ($result_level) {
            case static::STANDART_FIVE_LEVEL_ONE:
                return "Очень низкий уровень знаний (незнание)";
            case static::STANDART_FIVE_LEVEL_TWO:
                return "Низкий уровень знаний (неудовлетворительно)";
            case static::STANDART_FIVE_LEVEL_THREE:
                return "Средний уровень знаний (удовлетворительно)";
            case static::STANDART_FIVE_LEVEL_FOUR:
                return "Высокий уровень знаний (хорошо)";
            case static::STANDART_FIVE_LEVEL_FIVE:
                return "Очень высокий уровень знаний (отлично)";
            default:
                return "";
        }
    }
}
