<?php

namespace polyushina402\hangman\Controller;

use function polyushina402\hangman\View\showGame;
//use function polyushina402\hangman\View\showList;
//use function polyushina402\hangman\View\showReplay;
use function polyushina402\hangman\View\help;

function key($key, $id)
{
    if ($key == "--new" || $key == "-n") {
        startGame();
    } elseif ($key == "--list" || $key == "-l") {
        showList();
    } elseif ($key == "--replay" || $key == "-r") {
        showReplay($id);
    } elseif ($key == "--help" || $key == "-h") {
        help();
    } else {
        echo "Неверный ключ.";
    }
}

function startGame()
{
	$db = openDatabase();
	
	date_default_timezone_set("Europe/Moscow");
    $gameData = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");
    $playerName = getenv("username");
	
    $words = array("string", "letter", "artist", "arrive");
    $playWord = $words[array_rand($words)];
	
	$db->exec("INSERT INTO gamesInfo (
        gameData, 
        gameTime, 
        playerName, 
        playWord, 
        result
        ) VALUES (
        '$gameData', 
        '$gameTime', 
        '$playerName', 
        '$playWord', 
        'НЕ ЗАКОНЧЕНО')");

    $idGame = $db->querySingle("SELECT idGame FROM gamesInfo ORDER BY idGame DESC LIMIT 1");
	
    $remainingLetters = substr($playWord, 1, -1);
    $maxAnswers = strlen($remainingLetters);
    $maxFaults = 6;

    $progress = "______";
    $progress[0] = $playWord[0];
    $progress[-1] = $playWord[-1];

    $faultCount = 0;
    $answersCount = 0;
	$attempts = 0;

    do {
        showGame($faultCount, $progress);
        $letter = mb_strtolower(\cli\prompt("Буква"));
        $tempCount = 0;

        for ($i = 0; $i < strlen($remainingLetters); $i++) {
            if ($remainingLetters[$i] == $letter) {
                $progress[$i + 1] = $letter;
                $remainingLetters[$i] = " ";
                $answersCount++;
                $tempCount++;
            }
        }

        if ($tempCount == 0) {
            $faultCount++;
			$result = 0;
        } else {
            $result = 1;
        }
		
		$attempts++;
		
		$db->exec("INSERT INTO stepsInfo (
            idGame, 
            attempts, 
            letter, 
            result
            ) VALUES (
            '$idGame', 
            '$attempts', 
            '$letter', 
            '$result')");
    } while ($faultCount < $maxFaults && $answersCount < $maxAnswers);
	
	 if ($faultCount < $maxFaults) {
        $result = "ПОБЕДА";
    } else {
        $result = "ПОРАЖЕНИЕ";
    }
	
    showGame($faultCount, $progress);
    showResult($answersCount, $playWord);
	updateDB($idGame, $result);
}

function showList()
{
    $db = openDatabase();
    $query = $db->query('SELECT * FROM gamesInfo');
    while ($row = $query->fetchArray()) {
        echo "ID $row[0])\n    Дата:$row[1] $row[2]\n    Имя:$row[3]\n    Слово:$row[4]\n    Результат:$row[5]\n";
    }
}

function showReplay($id)
{
    $db = openDatabase();
    //$id = \cli\prompt("Введите id игры");
    $idGame = $db->querySingle("SELECT EXISTS(SELECT 1 FROM gamesInfo WHERE idGame='$id')");

    if ($idGame == 1) {
        $query = $db->query("SELECT letter, result from stepsInfo where idGame = '$id'");
        $playWord = $db->querySingle("SELECT playWord from gamesInfo where idGame = '$id'");

        $progress = "______";
        $progress[0] = $playWord[0];
        $progress[-1] = $playWord[-1];
        $remainingLetters = substr($playWord, 1, -1);
        $faultCount = 0;

        while ($row = $query->fetchArray()) {
            showGame($faultCount, $progress);
            $letter = $row[0];
            $result = $row[1];
            \cli\line("Буква: " . $letter);
            for ($i = 0; $i < strlen($remainingLetters); $i++) {
                if ($remainingLetters[$i] == $letter) {
                    $progress[$i + 1] = $letter;
                    $remainingLetters[$i] = " ";
                }
            }

            if ($result == 0) {
                $faultCount++;
            }
        }
        showGame($faultCount, $progress);

        \cli\line($db->querySingle("SELECT result from gamesInfo where idGame = '$id'"));
    } else {
        \cli\line("Такой игры не обнаружено!");
    }
}

function createDatabase()
{
    $db = new \SQLite3('gamedb.db');

    $gamesInfoTable = "CREATE TABLE gamesInfo(
        idGame INTEGER PRIMARY KEY,
        gameData DATE,
        gameTime TIME,
        playerName TEXT,
        playWord TEXT,
        result TEXT
    )";
    $db->exec($gamesInfoTable);


    $stepsInfoTable = "CREATE TABLE stepsInfo(
        idGame INTEGER,
        attempts INTEGER,
        letter TEXT,
        result INTEGER
    )";
    $db->exec($stepsInfoTable);

    return $db;
}

function openDatabase()
{
    if (!file_exists("gamedb.db")) {
        $db = createDatabase();
    } else {
        $db = new \SQLite3('gamedb.db');
    }
    return $db;
}

function updateDB($idGame, $result)
{
    $db = openDatabase();
    $db->exec("UPDATE gamesInfo
        SET result = '$result'
        WHERE idGame = '$idGame'");
}

//результат игры
function showResult($answersCount, $playWord)
{
    if ($answersCount == 4) {
        \cli\line("Вы победили!");
    } else {
        \cli\line("\nВы проиграли!");
    }
    \cli\line("\nИгровое слово было: $playWord\n");
}
