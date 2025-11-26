<?php
class Validator {
    public static function clean($data) {
        return htmlspecialchars(trim($data));
    }
}
