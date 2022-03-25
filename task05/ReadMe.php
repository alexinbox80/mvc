<?php
// 1. Реализовать на PHP пример Декоратора, позволяющий отправлять уведомления несколькими различными способами
// (описан в этой методичке).

// 2. Реализовать паттерн Адаптер для связи внешней библиотеки (классы SquareAreaLib и CircleAreaLib) вычисления
// площади квадрата (getSquareArea) и площади круга (getCircleArea) с интерфейсами ISquare и ICircle имеющегося
// кода. Примеры классов даны ниже. Причём во внешней библиотеке используются для расчётов формулы нахождения через
// диагонали фигур, а в интерфейсах квадрата и круга — формулы, принимающие значения одной стороны и длины окружности
// соответственно.

// Внешняя библиотека:
class CircleAreaLib
{
    public function getCircleArea(int $diagonal)
    {
        $area = (M_PI * $diagonal**2))/4;

       return $area;
   }
}

class SquareAreaLib
{
    public function getSquareArea(int $diagonal)
    {
        $area = ($diagonal**2)/2;

        return $area;
    }
}

// Имеющиеся интерфейсы:
interface ISquare
{
    function squareArea(int $sideSquare);
}

interface ICircle
{
    function circleArea(int $circumference);
}