<?php

// ● поиск элемента массива с известным индексом,
// сложность алгоритма O(n)
$array = [1, 4, 6, 7, 2, 14, 5];
$index = 3;
for ($i = 0; $i < count($array); $i++) {
    if ($i === $index) {
        echo $array[$index] . "\n";
    }
}

// ● дублирование массива через foreach,
// сложность алгоритма O(n)
$array = [1, 4, 6, 7, 2, 14, 5];
$array_copy = [];

foreach ($array as $item) {
    $array_copy [] = $item;
}

var_dump($array);
var_dump($array_copy);

// ● рекурсивная функция нахождения факториала числа.
// сложность алгоритма O(n)

function factorial(int $number) {
    if ($number === 0) {
        return 1;
    } else {
        return $number * factorial($number - 1);
    }
}

echo factorial(6) . "\n";