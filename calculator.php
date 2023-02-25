<?php

function calcFromString(string $string) : float{

    //Code that analyze the presence of brackets and calculates them in the order: (), [], {};
    $brackets = array('()', '[]', '{}');
    //Variables that contain the position of the open and close brackets;
    $open = 0;
    $close = 0;
    //Cycle that repeats the analysis of the string for each type of brackets;
    foreach($brackets as $p){
        //Cycle that analyzes the string looking for open and the corresponding close brackets;
        for($i = 0; $i < strlen($string); $i++){
            switch($string[$i]){
                case $p[0]:
                    $open = $i;
                    break;
                case $p[1]:
                    //If the corresponding open and close brackets are found, the string is cut and the function is called again;
                    $close = $i;
                    $string = substr($string, 0, $open) . calcFromString(substr($string, $open + 1, $close - 1)) . substr($string, $close + 1);
                    return calcFromString($string);
            }
        }
    }
    //Code that analyzes the string looking for the basic operators and calculate them in the order: ^, r, *, /, +, -;
    //Cycle that analyzes the string looking for the basic operators: +, -;
    for($i = 0; $i < strlen($string); $i++){
        switch($string[$i]){
            case '+':
                return calcFromString(substr($string, 0, $i)) + calcFromString(substr($string, $i + 1));
            case '-':
                return calcFromString(substr($string, 0, $i)) - calcFromString(substr($string, $i + 1));
        }
    }
    //Cycle that analyzes the string looking for the basic operators: *, /;
    for($i = 0; $i < strlen($string); $i++){
        switch($string[$i]){
            case '*':
                return calcFromString(substr($string, 0, $i)) * calcFromString(substr($string, $i + 1));
            case '/':
                return calcFromString(substr($string, 0, $i)) / calcFromString(substr($string, $i + 1));
        }
    }
    //Cycle that analyzes the string looking for the basic operators: ^, r;
    for($i = 0; $i < strlen($string); $i++){
        switch($string[$i]){
            case '^':
                return calcFromString(substr($string, 0, $i)) ** calcFromString(substr($string, $i + 1));
            case 'r':
                return calcFromString(substr($string, $i + 1)) ** (1 / calcFromString(substr($string, 0, $i)));
        }
    }
    //Code that performs the basic conversions and the calculation of negative values, returning the final value;
    switch($string[0]){
        //Basic conversions;
        case 'b':
            return bindec(substr($string, 1));
        case 'o':
            return octdec(substr($string, 1));
        case 'h':
            return hexdec(substr($string, 1));
        //Negative values;
        case '-':
            return -calcFromString(substr($string, 1));
        case '+':
            return calcFromString(substr($string, 1));
        //Final value;
        default:
            return $string;
    }
}

?>
