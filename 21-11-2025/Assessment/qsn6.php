<?php
function rotateNumber($n) {
    $str = strval($n);
    $len = strlen($str);

    for ($i = 0; $i < $len; $i++) {
        $str = substr($str, 1) . $str[0];
        echo $str . "<br>";
    }
}

rotateNumber(1234);
?>
