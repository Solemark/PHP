<?php
function fizz_buzz(int $fizz, int $buzz, int $max): string{
    $output = "\n";
    $i = 1;
    while($i <= $max){
        if(0 == $i % $fizz){
            $output = $output . "fizz";
        }
        if(0 == $i % $buzz){
            $output = $output . "buzz";
        }
        if("z" !== $output[strlen($output)-1]){
            $output = $output . strval($i);
        }
        $output = $output . "\n";
        $i++;
    }
    return $output;
}

echo fizz_buzz(3, 5, 20);