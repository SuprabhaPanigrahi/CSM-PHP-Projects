<?php
function isPrime($n) {
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) return false;
    }
    return true;
}

function isPalindrome($n) {
    return strval($n) == strrev($n);
}

for ($i = 1; $i <= 10000; $i++) {
    if (isPrime($i) && isPalindrome($i)) {
        echo $i . " ";
    }
}
?>
