<?php

namespace App\Helpers;

/*
 * Хэлпер для выполнения преобразований над величинами, а также синтаксического форматирования
 *
 * @author Oleg Pyatin
 */
class ConvertHelper
{
    /**
     * Конвертируем количество секунд в языковое представление интервала времени
     *     (в часах, минутах, секундах) или указания что такого ограничения нет (основное место - тесты)
     *
     * @param int $time   Количество секунд которые нужно преобразовать
     * @return string   Строковое представление времени
     */
    public static function convertSecondsToTimeInterval(?int $time): string
    {
        if (!empty($time)) {

            $hours = (int)($time / 3600);
            $minutes = (int)($time % 3600 / 60);
            $seconds = $time %  60;

            $message = ($hours) ? $hours.' ч. ' : '';
            $message .= ($minutes) ? $minutes.' мин. ': '';
            $message .= ($seconds) ? $seconds.' сек.' : '';

            return $message;

        } else {
            return 'Нет';
        }
    }
}
