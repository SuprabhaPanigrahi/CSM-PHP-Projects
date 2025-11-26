<?php
function rearrangeHighLow($arr) {
    sort($arr);
    $result = [];
    $i = count($arr) - 1; // highest
    $j = 0;               // lowest

    while ($j <= $i) {
        if ($i >= $j) $result[] = $arr[$i--];
        if ($j <= $i) $result[] = $arr[$j++];
    }

    return $result;
}

print_r(rearrangeHighLow([1,2,3,4,5,6,7]));
?>
