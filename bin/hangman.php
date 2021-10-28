<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use function polyushina402\hangman\Controller\key;

if (isset($argv[1])) {
    if ($argv[1] == "--replay" || "-r") {
        key($argv[1], $argv[2]);
    } else {
        key($argv[1], null);
    }
} else {
    key("-n", null);
}
