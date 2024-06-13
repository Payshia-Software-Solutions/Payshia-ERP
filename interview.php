<?php

$array1 = array(2, 4, 1, 5, 7, 3);
$resultArray = array();


for ($i = 0; $i < count($array1); $i++) {
    if ($i == count($array1) - 1) {
        $newValue = $array1[$i] + $array1[0];
        $resultArray[0] = $newValue;
    } else {
        $newValue = $array1[$i] + $array1[$i + 1];

        $resultArray[$i] = $newValue;
    }
}

// print_r($resultArray);
