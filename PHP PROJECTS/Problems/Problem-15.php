<?php
/*
 * Problem : Guess the number
 * WAP to generate a random number between 1 to 10 and ask the user to guess the number.
 * If the user guesses the correct number, display a success message.
 * If the guess is incorrect, display an error message and allow the user to guess again until they get it right.
 */

$randomNumber = rand(1, 10);

do {
    $guess = readline("Guess the randomly generated number (1 to 10): ");
    
    if ($guess < $randomNumber) {
        echo "Too low! Try again.\n";
    } elseif ($guess > $randomNumber) {
        echo "Too high! Try again.\n";
    } else {
        echo "Congratulations! You guessed it right: $randomNumber\n";
    }

} while ($guess != $randomNumber);
?>
