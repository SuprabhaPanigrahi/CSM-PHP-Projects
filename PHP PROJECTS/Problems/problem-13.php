<?php 

/*
 * Problem :LCM & GCD
 * WAP to find LCM & GCD of two numbers
 * INPUT : 12, 15
 * OUTPUT : LCM = 60, GCD = 3
 */

// $n1 = 10;
// $n2 = 20;
// $lowest = $n1;
// $var = []; 
// if ($n1 < $n2) {
//     $lowest = $n2;
// }
// for ($i = 2; $i <= $lowest; $i++) {
//     while ($n1 != 1 || $n2 != 1) {
//         if ($n1 % $i == 0 && $n2 % $i == 0) {
//             $var[] = $i;
//         }
//         $n1 = $n1 / $i;
//         $n2 = $n2 / $i;
//     }
// }
// $mul = 1;
// for($i=0;$i<count($var);$i++){
//     $mul = $mul * $i;
// }

$n1 = readline("enter first number :");
$n2 = readline("enter second number :");

$original_n1 = $n1;
$original_n2 = $n2;

$lowest = $n1;
if($n1>$n2){
    $lowest = $n2;
}

$var = [];

for ($i = 2; $i <= $lowest; $i++) {
    while ($n1 % $i == 0 && $n2 % $i == 0) {
        $var[] = $i;
        $n1 = $n1 / $i;
        $n2 = $n2 / $i;
    }
}

$gcd = 1;
for ($i = 0; $i < count($var); $i++) {
    $gcd = $gcd * $var[$i];
}

echo "GCD of $original_n1 and $original_n2 is : $gcd\n";

$lcm = ($original_n1 * $original_n2) / $gcd;
echo "LCM of $original_n1 and $original_n2 is : $lcm\n";

?>
