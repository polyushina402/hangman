<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use function polyushina402\hangman\Controller\key;

if (isset($argv[1])) {
    $key = $argv[1];
	if ($key == "--replay" || "-r") {
		key($key, $argv[2]);
	} else {
		$argv[2] = NULL;
		key($key, $argv[2]);
	}
} else {
	$argv[2] = NULL;
    $key = "-n";
    key($key, $argv[2]);
}
