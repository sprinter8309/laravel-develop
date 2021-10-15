<?php

namespace App\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Класс для обеспечения логики работы с данными в виджете простого GridView
 *
 * @author Oleg Pyatin
 */
class GridViewProvider
{
    //  Переменная для сортировки столбцов
    private $data;

    private $data_chunks;

    // Переменная отслеживания режима пагинации
    private $ajax_mode;
    /**
     * @var string  Поле для хранения мени поля по которому идет фильтрация
     */
    private $filter_field;
    /**
     * @var int  Поле для хранения номера страницы
     */
    private $page;
    /**
     * @var array  Переменная для хранения маршрутов
     */
    private $move_routes;

    /**
     * Функция генерации нового провайдера из входных данных (запроса и данных из БД)
     *
     * @param Collection $fullInputData  Входные данные для отображения
     * @param Request $request  Входной запрос (если имеются GET-параметры считаем AJAX-ом)
     * @return GridViewProvider
     */
    public static function getDataProvider(Collection $fullInputData, Request $request)
    {
        $new_provider = new static();

        // Загрузка из параметров запроса данных пагинации и фильтрации (если есть)
        //    Размер пагинации указываем в виджете (передаем в функцию)
        if (count($request->all())>0) {
            $new_provider->ajax_mode = true;
        } else {
            $new_provider->ajax_mode = false;
        }

        $new_provider->data = $fullInputData;
        $new_provider->page = $request->get('page') ?? 0;
        $new_provider->filter_field = null;  // Пока ставим null
        $new_provider->move_routes = [];

        return $new_provider;
    }

    /**
     * Функция проверки текущий запрос идет ли в AJAX-режиме (есть ли у него GET-параметры) и нужно
     *     ли соответственно рендерить представление
     *
     * @return bool
     */
    public function checkAjaxMode(): bool
    {
        return $this->ajax_mode;
    }

    /**
     * Функция всех порций данных для провайдера
     *
     * @param int $page_size  Размер выдаваемой порции данных
     */
    public function getDataChunks(int $page_size): array
    {
        return $this->data_chunks = $this->data->chunk($page_size)->all();
    }

    /**
     * Вернуть номер текущей страницы (нужен для вывода данных в GridView)
     *
     * @return int Номер текущей страницы
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * Функция создания роутинга для GridView
     *
     * @return array  Массив с маршрутами для навигации
     */
    public function constructNavigationRoutes()
    {
        if ($this->page > 0) {
            $this->move_routes["previous"] = "?" . http_build_query(['page'=>$this->page - 1 , 'filter'=>$this->filter_field]);
        } else {
            $this->move_routes["previous"] = null;
        }

        if ($this->page<(count($this->data_chunks)-1)) {
            $this->move_routes["next"] = "?" . http_build_query(['page'=>$this->page + 1 , 'filter'=>$this->filter_field]);
        } else {
            $this->move_routes["next"] = null;
        }

        return $this->move_routes;
    }

    /**
     * Функция для получения следующей порции данных (основная функция класса)
     *
     * @param int $page_size  Размер выдаваемой порции данных
     */
    public function getPageData(int $page_size)
    {
        return $this->data->splice($this->page * $page_size, $page_size);
    }
}
