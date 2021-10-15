<?php

namespace Modules\Exam\Entities;

use App\Components\BaseDto;

/**
 * Класс для хранения указаний по обработке результатов теста, которые берутся из БД
 *
 * @author Oleg Pyatin
 */
class ExamResultActions extends BaseDto
{
    /**
     * @var string  Тип расчета (стандартный - standart, пользовательский - custom (с отдельными сообщениями для
     *                  каждой оценки и пр))
     */
    public $type;
    /**
     * @var int  Количество градации в оценке (стандартной, 3-бальная, 5-бальная и др)
     */
    public $degrees_amount;
    /**
     * @var string  Тип обработки результатов (стандартный, усложненный (требовательнее))
     */
    public $process_type;
    /**
     * @var bool  Указываем выводить ли место полученной оценки среди общей градации
     */
    public $marks_degree_show;
    /**
     * @var string  Указываем в какой форме выводить ответы при расчете результатов
     */
    public $answers_show;

    /**
     * Функция для распаковки JSON данных из БД
     *
     * @param string $json_data  Данные в формате json которые загрузим в DTO
     * @return type
     */
    public static function getFromJson(string $json_data)
    {
        return static::loadFromArray(json_decode($json_data, true));
    }
}
