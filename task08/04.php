<?php

$count = 2;
//$m = 100; // 2, 5
//$m = 13195; // 5, 7, 13, 29
$number = 600851475143; // 71, 839, 1471, 6857 - ответ

while ($number != 1) {
    $flag = false;

    while ($number % $count === 0) {
        $flag = true;
        $number /= $count;
    }

    if ($flag) {
        echo "$count - simple divisor\n";
    }

    $count++;
}
