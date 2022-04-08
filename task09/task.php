<?php
// 1. Создать массив на миллион элементов и отсортировать его различными способами. Сравнить скорости.
//
// 2. Реализовать удаление элемента массива по его значению. Обратите внимание на возможные дубликаты!
//
// 3. Подсчитать практически количество шагов при поиске описанными в методичке алгоритмами.
//
// 4. * Выписав первые шесть простых чисел, получим 2, 3, 5, 7, 11 и 13. Очевидно, что 6-е простое число — 13.
// Какое число является 10001-м простым числом?
// [10001] => 104743

class ArrayOperations
{
    private $array;
    const DELETE_VALUE = 100;

    public function __construct($length)
    {
        for ($i = 0; $i < $length; $i++) {
            $this->array[$i] = rand(0, self::DELETE_VALUE);
        }
    }

    public function getArray()
    {
        return $this->array;
    }

    public function showArray($array = null)
    {
        if (isset($array)) {
            for ($i = 0; $i < count($array); $i++) {
                echo $array[$i] . ' ';
            }
        } else {
            for ($i = 0; $i < count($this->array); $i++) {
                echo $this->array[$i] . ' ';
            }
        }

        echo "<br>\n";
    }

// 1)
    public function bubbleSort($array)
    {
        do {
            $swapped = false;
            for ($i = 0, $c = count($array) - 1; $i < $c; $i++) {
                if ($array[$i] > $array[$i + 1]) {
                    list($array[$i + 1], $array[$i]) =
                        array($array[$i], $array[$i + 1]);

                    $swapped = true;
                }
            }
        } while ($swapped);

        return $array;
    }

    public function quickSort($array)
    {
        $loe = $gt = array();

        if (count($array) < 2) {
            return $array;
        }

        $pivot_key = key($array);
        $pivot = array_shift($array);

        foreach ($array as $val) {
            if ($val <= $pivot) {
                $loe[] = $val;
            } elseif ($val > $pivot) {
                $gt[] = $val;
            }
        }

        return array_merge($this->quickSort($loe), array($pivot_key => $pivot), $this->quickSort($gt));
    }


// To heapify a subtree rooted with node i which is
// an index in arr[]. n is size of heap
    public function heapify(&$array, $n, $i)
    {
        $largest = $i; // Initialize largest as root
        $l = 2 * $i + 1; // left = 2*i + 1
        $r = 2 * $i + 2; // right = 2*i + 2

        // If left child is larger than root
        if ($l < $n && $array[$l] > $array[$largest]) {
            $largest = $l;
        }

        // If right child is larger than largest so far
        if ($r < $n && $array[$r] > $array[$largest]) {
            $largest = $r;
        }

        // If largest is not root
        if ($largest != $i) {

            list($array[$largest], $array[$i]) =
                array($array[$i], $array[$largest]);

            // Recursively heapify the affected sub-tree
            $this->heapify($array, $n, $largest);
        }
    }

// main function to do heap sort
    public function heapSort($array, $n)
    {
        // Build heap (rearrange array)
        for ($i = $n / 2 - 1; $i >= 0; $i--) {
            $this->heapify($array, $n, $i);
        }

        // One by one extract an element from heap
        for ($i = $n - 1; $i > 0; $i--) {
            // Move current root to end

            list($array[$i], $array[0]) =
                array($array[0], $array[$i]);

            // call max heapify on the reduced heap
            $this->heapify($array, $i, 0);
        }

        return $array;
    }

// 2)
    public function lineSearchMethod($array, $value)
    {
        $searchIterations = 0;

        for ($i = 0; $i < count($array); $i++) {
// 3)
            $searchIterations++;
            if ($array[$i] === $value) {
                echo "Search iterations = " . $searchIterations . " <br>\n";
                return $i;
            }
        }
        echo "Search iterations = " . $searchIterations . " <br>\n";
        return -1;
    }

    public function binarySearchMethod($array, $value)
    {
        // Начальное значение переменной
        $length = count($array);
        $lower = 0;
        $high = $length - 1;

        $searchIterations = 0;

        // Выход, если самая низкая точка больше самой высокой точки
        while ($lower <= $high) {
            // Используем среднюю точку в качестве ориентира для сравнения
            $middle = intval(($lower + $high) / 2);
// 3)
            $searchIterations++;
            if ($array[$middle] > $value) {
                // Номер поиска меньше контрольной точки, и правая часть отбрасывается
                $high = $middle - 1;
            } else if ($array[$middle] < $value) {
                // Число поиска больше контрольной точки, а левая часть отбрасывается
                $lower = $middle + 1;
            } else {
                echo "Search iterations = " . $searchIterations . " <br>\n";
                // Номер поиска равен контрольной точке, затем он находится и возвращается

                while (($middle >= 0) && ($array[$middle] === $value))
                {
                    $middle--;
                }

                return ++$middle;
            }
        }
        echo "Search iterations = " . $searchIterations . " <br>\n";
        // Не найдено, возвращаем -1
        return -1;
    }

    public function deleteArrayItem($array, $position, $value)
    {
        $index = $position;
        while (($index < count($array)) && ($array[$index] === $value))
        {
            unset($array[$index]);
            $index++;
        }

        return array_values($array);
    }
}

//const ARRAY_SIZE = 1000000;
//const ARRAY_SIZE = 100000;
const ARRAY_SIZE = 10000;
const DELETE_VALUE = 100;

$arr = new ArrayOperations(ARRAY_SIZE);

echo "<br>\n";
$arr->showArray();
$start = microtime(true);
$array = $arr->bubbleSort($arr->getArray());
$stop = microtime(true);
echo '$arr->bubbleSort() ' . $stop - $start . " sec <br>\n";
//$arr->showArray($array);

echo "<br>\n";
//$arr->showArray();
$start = microtime(true);
$array = $arr->quickSort($arr->getArray());
$stop = microtime(true);
echo '$arr->quickSort() ' . $stop - $start . " sec <br>\n";
//$arr->showArray($array);

echo "<br>\n";
//$arr->showArray();
$start = microtime(true);
$array = $arr->heapSort($arr->getArray(), count($arr->getArray()));
$stop = microtime(true);
echo '$arr->heapSort() ' . $stop - $start . " sec <br>\n";
//$arr->showArray($array);

echo "<br>\n";
//$arr->showArray();
$array = $arr->getArray();
$start = microtime(true);
$array = sort($array);
$stop = microtime(true);
echo 'php sort() ' . $stop - $start . " sec <br>\n";
//$arr->showArray($array);

echo "<br>\n";
$array = $arr->getArray();
$array = $arr->heapSort($array, count($array));

$delete_value = rand(0, $arr::DELETE_VALUE);

$start = microtime(true);
$item = $arr->lineSearchMethod($array, $delete_value);
$stop = microtime(true);

$arr->showArray($array);
echo "delete value = $delete_value <br>\n";
echo "item = $item <br>\n";
if ($item != -1) {
    $array1 = $arr->deleteArrayItem($array, $item,$delete_value);
}

echo 'php lineSearchMethod() ' . $stop - $start . " sec <br>\n";
$arr->showArray($array1);

echo "<br>\n";

$start = microtime(true);
$item = $arr->binarySearchMethod($array, $delete_value);
$stop = microtime(true);

//$arr->showArray($array);
echo "delete value = $delete_value <br>\n";
echo "item = $item <br>\n";
if ($item != -1) {
    $array1 = $arr->deleteArrayItem($array, $item,$delete_value);
}

echo 'php binarySearchMethod() ' . $stop - $start . " sec <br>\n";
$arr->showArray($array1);
