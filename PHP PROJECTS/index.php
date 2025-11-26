<?php
class Demo {
    public static $count = 0;   // static variable to count objects

    public function __construct() {
        self::$count++;         // increment count whenever object is created
    }
}

// creating objects
$obj1 = new Demo();
$obj2 = new Demo();
$obj3 = new Demo();

echo "Total number of objects created: " . Demo::$count;
?>
