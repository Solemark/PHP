<?php
function reverse_array($arr){
    $x = 0;
    $y = 0;
    $c = count($arr)-1;
    for($i = 0; $i < $c; $i++){
        $x = $arr[$i];
        $y = $arr[$c];
        $arr[$i] = $y;
        $arr[$c] = $x;
        $c--;
    }
    for($i = 0; $i < count($arr); $i++){
        echo $arr[$i].", ";
    }
    echo "\n";
}
reverse_array([1, 2, 3, 4, 5]);
reverse_array([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
reverse_array(["Hello", "How", "Are", "You", "Today"]);