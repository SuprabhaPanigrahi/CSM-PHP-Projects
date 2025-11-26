<?php
function longestPalindrome($s) {
    $max = "";

    for ($i = 0; $i < strlen($s); $i++) {
        for ($j = $i; $j < strlen($s); $j++) {
            $sub = substr($s, $i, $j - $i + 1);
            if ($sub == strrev($sub) && strlen($sub) > strlen($max)) {
                $max = $sub;
            }
        }
    }

    return $max;
}

echo longestPalindrome("babad");
?>
