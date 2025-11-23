<?php


$mailid  = 'rayy@example.com';     // Assigns an email address to the variable $mailid
$user = strstr($mailid, '@', true); // Finds the first occurrence of '@' in $mailid and returns the substring before it
echo $user."\n";                    // Outputs the substring before the '@' character in the email address


?>
