<?php
function arrayCol($count, $col) {
    $elems = $count;
    $column = $col;

    $array = array();
    for ($i = 1; $i <= $elems; $i++)
        $array[] = $i;

    // нарезка на столбики
    $cols = array();
    while ($column > 1 + count($cols)) {
        $last = floor(count($array) / ($column - count($cols)));
        list($array, $tail) = array_chunk($array, count($array) - $last);
        $cols[] = $tail;
    }
    $cols[] = $array;
    $cols = array_reverse($cols);
    return $cols;
}