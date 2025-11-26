<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $var ?></title>
</head>
<body>
    <h3><?php echo $var ?></h3>
    <a href="https://www.w3resource.com/php-exercises/php-basic-exercises.php">Go to the <?php echo $var ?></a>
</body>
</html> -->



<?php
// Write a PHP script to get the PHP version and configuration information.

phpinfo();
?>

<?php
// Write a PHP script to display the following strings.
// Sample String :
// 'Tomorrow I \'ll learn PHP global variables.
// 'This is a bad command : del c:\\*.*'
// Expected Output :
// Tomorrow I 'll learn PHP global variables.
// This is a bad command : del c:\*.*

// echo "Tomorrow I'll learn PHP global variables." . "\n";
// echo "This is a bad command : del c:\\*.*" . "\n";
?>

<?php
// $var = 'PHP Tutorial'. Put this variable into the title section, h3 tag and as an anchor text within an HTML document
// $var = 'PHP Tutorial';
?>

<!-- 4. Basic HTML Form and Echo Name

Create a simple HTML form and accept the user name and display the name through PHP echo statement. -->

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
    <title>Document</title>
</head>
<body>
    <form action="Exercise1.php" method="post">
        <label for="name">Please input your name:</label><br>
        <input type="text" id="name" name="name" />
        <button type="submit">Submit Name</button>
    </form>

    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])){
        $name = $_POST['name'];
        echo "<h3>Hello $name</h3>";
    }
    ?>
</body>
</html> -->


<!-- <?php
// 5. Get Client IP Address

// Write a PHP script to get the client IP address.
echo $_SERVER['REMOTE_ADDR'];
?> -->



<?php
// 6. Browser Detection
// Display the text "Your User Agent is :" followed by the user agent string from the HTTP request
// echo "Your User Agent is :" . $_SERVER['HTTP_USER_AGENT'];
?>



<?php
// 9. Change Color of First Character
// Write a PHP script, which changes the color of the first character of a word.

// $text = 'PHP Tutorial';
// $text = preg_replace('/(\b[a-z])/i', '<span style="color:red;">\1</span>', $text);
// echo $text;
?>


<?php
echo "<table border=1 cellspacing=0 cellpadding=0> 
<tr><th> Salary of Mr. A is </th><td> 1000$ </td></tr>
<tr><th>Salary of Mr. B is </th><td> 1200$ </td></tr>
<tr><th>Salary of Mr. C is </th><td> 1400$ </td></tr>"
?>


