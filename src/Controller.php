<?php

namespace polyushina402\hangman\Controller;

use RedBeanPHP\R;

use function polyushina402\hangman\View\showGame;
use function polyushina402\hangman\View\help;

R::setup("sqlite: DB.db");

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
    date_default_timezone_set("Europe/Moscow");
    $gameDate = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");
    $playerName = getenv("username");

    $words = array("string", "letter", "artist", "arrive");
    $playWord = $words[array_rand($words)];

    $db = R::dispense('games');
    $db->date = $gameDate;
    $db->time = $gameTime;
    $db->name = $playerName;
    $db->word = $playWord;
    $db->result = "НЕ ЗАКОНЧЕНО";
    $idGame = R::store($db);

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

        $db = R::dispense('steps');
        $db->idGame = $idGame;
        $db->attempts = $attempts;
        $db->letter = $letter;
        $db->result = $result;
        R::store($db);
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
    $db = R::getAll('SELECT * FROM games');
    if (sizeof($db) !== 0) {
        foreach ($db as $row) {
            \cli\line("ID: $row[id]");
            \cli\line("Дата: $row[date]");
            \cli\line("Время: $row[time]");
            \cli\line("Имя: $row[name]");
            \cli\line("Загаданное слово: $row[word]");
            \cli\line("Результат: $row[result]");
        }
    } else {
        \cli\line("Баа данных пуста.");
    }
}

function showReplay($id)
{
    $db = R::getAll("SELECT letter, result from steps where id_game = '$id'");
    $playWord = R::getAll("SELECT word from games where id = '$id'");
    $progress = "______";
    $progress[0] = $playWord[0]["word"][0];
    $progress[-1] = $playWord[0]["word"][-1];
    $remainingLetters = substr($playWord[0]["word"], 1, -1);
    $faultCount = 0;
    if (sizeof($db) !== 0) {
        foreach ($db as $row) {
            showGame($faultCount, $progress);
            $letter = $row["letter"];
            $result = $row["result"];
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

        \cli\line(R::getCell("SELECT result from games where id = '$id'"));
    } else {
        \cli\line("Отсутствуют данные по игре, либо ходы не совершались.");
    }
}

function updateDB($idGame, $result)
{
    $db = R::load('games', $idGame);
    $db->result = $result;
    R::store($db);
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

R::close();
