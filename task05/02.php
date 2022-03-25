<?php
// 2. Реализовать паттерн Адаптер для связи внешней библиотеки (классы SquareAreaLib и CircleAreaLib) вычисления
// площади квадрата (getSquareArea) и площади круга (getCircleArea) с интерфейсами ISquare и ICircle имеющегося
// кода. Примеры классов даны ниже. Причём во внешней библиотеке используются для расчётов формулы нахождения через
// диагонали фигур, а в интерфейсах квадрата и круга — формулы, принимающие значения одной стороны и длины окружности
// соответственно.

// Внешняя библиотека:
//class CircleAreaLib
//{
//    public function getCircleArea(int $diagonal)
//    {
//        $area = (M_PI * $diagonal ** 2) / 4;
//
//        return $area;
//    }
//}

/**
 * Целевой класс объявляет интерфейс, с которым может работать клиентский код.
 */

interface ICircleAreaLib
{
    public function getCircleArea(int $diagonal);
}

interface ISquareAreaLib
{
    public function getSquareArea(int $diagonal);
}

class CircleAreaLib implements ICircleAreaLib
{
    public function getCircleArea(int $diagonal)
    {
        $area = (M_PI * $diagonal ** 2) / 4;

        return $area;
    }
}

class SquareAreaLib implements ISquareAreaLib
{
    public function getSquareArea(int $diagonal)
    {
        $area = ($diagonal ** 2) / 2;

        return $area;
    }
}

/**
 * Адаптируемый класс содержит некоторое полезное поведение, но его интерфейс
 * несовместим с существующим клиентским кодом. Адаптируемый класс нуждается в
 * некоторой доработке, прежде чем клиентский код сможет его использовать.
 */

interface ISquare
{
    function squareArea(float $sideSquare);
}

interface ICircle
{
    function circleArea(float $circumference);
}

class Adaptee implements ISquare, ICircle
{
    public function squareArea(float $sideSquare)
    {
        return $sideSquare * $sideSquare;
    }

    public function circleArea(float $circumFerence)
    {
        return $circumFerence * $circumFerence / (4 * M_PI);
    }
}

/**
 * Адаптер делает интерфейс Адаптируемого класса совместимым с целевым
 * интерфейсом.
 */
class Adapter implements ISquareAreaLib, ICircleAreaLib
{
    private $adaptee;

    public function __construct(Adaptee $adaptee)
    {
        $this->adaptee = $adaptee;
    }

    public static function getSquareSide(int $diagonal)
    {
        return $diagonal / sqrt(2);
    }

    public function getSquareArea(int $diagonal)
    {
        $side = self::getSquareSide($diagonal);

        return 'Adapter: ' . $this->adaptee->squareArea($side);
    }

    public static function getCircumFerence(int $diagonal)
    {
        return M_PI * $diagonal;
    }

    public function getCircleArea(int $diagonal)
    {
        $circumFerence = self::getCircumFerence($diagonal);

        return 'Adapter: ' . $this->adaptee->circleArea($circumFerence);
    }
}

/**
 * Клиентский код поддерживает все классы, использующие целевой интерфейс.
 */

class ClientCode
{
    public function clientCodeSquare(ISquareAreaLib $target, int $diagonal)
    {
        echo 'Square = ' . $target->getSquareArea($diagonal);
    }

    public function clientCodeCircle(ICircleAreaLib $target, int $diagonal)
    {
        echo 'Square = ' . $target->getCircleArea($diagonal);
    }
}


$squareDiagonal = 5;
echo "Client: I can work just fine with the SquareAreaLib():\n";
$target = new SquareAreaLib();
(new ClientCode())->clientCodeSquare($target, $squareDiagonal);
echo "\n\n";

$adaptee = new Adaptee();
echo "Client: The Adaptee class has a weird interface. See, I don't understand it:\n";
echo 'Adaptee: ' . round($adaptee->squareArea(Adapter::getSquareSide($squareDiagonal)), 1);
echo "\n\n";

echo "Client: But I can work with it via the Adapter:\n";
$adapter = new Adapter($adaptee);
(new ClientCode())->clientCodeSquare($adapter, $squareDiagonal);
echo "\n\n";


$circleDiagonal = 4;
echo "Client: I can work just fine with the CircleAreaLib():\n";
$target = new CircleAreaLib();
(new ClientCode())->clientCodeCircle($target, $circleDiagonal);
echo "\n\n";

$adaptee = new Adaptee();
echo "Client: The Adaptee class has a weird interface. See, I don't understand it:\n";
echo 'Adaptee: ' . $adaptee->CircleArea(Adapter::getCircumFerence($circleDiagonal));
echo "\n\n";

echo "Client: But I can work with it via the Adapter:\n";
$adapter = new Adapter($adaptee);
(new ClientCode())->clientCodeCircle($adapter, $circleDiagonal);
echo "\n\n";
