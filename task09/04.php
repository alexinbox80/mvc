<?php
// Решето Эратосфена
//
// Этот алгоритм как бы «просеивает» все числа от 0 до максимального N через условное решето несколько раз.
// Сначала последовательно исключаются все числа, кратные 2. Само число добавляется в массив простых чисел.
// Далее из оставшихся исключаются все числа, кратные следующему простому числу — трем. 4 уже было удалено из
// исходного массива, соответственно, следом удаляются все числа, кратные 5, и так далее, пока не будут
// перебраны все числа.


function getPrimes($max_number)
{
    $primes = [];
    $is_composite = [];

    for ($i = 4; $i <= $max_number; $i += 2) {
        $is_composite[$i] = true;
    }

    $next_prime = 3;

    while ($next_prime <= (int)sqrt($max_number)) {
        for ($i = $next_prime * 2; $i <= $max_number; $i += $next_prime) {
            $is_composite[$i] = true;
        }

        $next_prime += 2;

        while ($next_prime <= $max_number && isset($is_composite[$next_prime])) {
            $next_prime += 2;
        }
    }

    for ($i = 2; $i <= $max_number; $i++) {
        if (!isset($is_composite[$i]))
            $primes[] = $i;
    }

    return $primes;
}

print_r(getPrimes(105000)); // [10001] => 104743
