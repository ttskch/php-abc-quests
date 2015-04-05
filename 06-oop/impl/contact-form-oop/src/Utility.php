<?php

class Utility
{
    public static function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    public static function generateCsrfKey()
    {
        return $_SESSION['csrf_key'] = sha1(uniqid(mt_rand(), true));
    }

    public static function checkCsrfKey($key)
    {
        if (!isset($key) || !isset($_SESSION['csrf_key']) || $_SESSION['csrf_key'] !== $key) {
            return false;
        }

        return true;
    }
}
