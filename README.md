# Лабораторная работа 3. Реализация консольного варианта игры без работы с базой данных
### Мордовский Государственный Университет, ФМиИТ, 402 группа, Полюшина Д.В.

### Описание
 Компьютер загадывает английское слово из шести букв Ваша задача - угадать буквы, а затем и все слово целиком.
 Если Вы правильно угадывает букву, компьютер вписывает ее в клетку. Если ошибаетесь, то рисует одну из частей тела 
 повешенного человека. Чтобы победить, Вам нужно угадать все буквы в слове до того, как повешенный человечек будет
 полностью нарисован.

### Псевдографика
+---+    +---+    +---+    +---+    +---+    +---+    +---+
    |    0   |    0   |    0   |    0   |    0   |    0   |
    |        |    |   |   /|   |   /|\  |   /|\  |   /|\  |
    |        |        |        |        |   /    |   / \  |
   ===      ===      ===      ===      ===      ===      ===


### Режим работы приложения определяется при запуске по аргументам командной строки
* --new. Новая игра. Этот же режим используется по умолчанию, если программа запускается без параметров.
* --list. Вывод списка всех сохраненных игр.
* --replay id. Повтор игры с идентификатором id.
* --help. Вывод краткой информации о приложении и доступных ключах для запуска в разных режимах.

###Минимальная версия
Composer version 2.1.6 2021-08-19 17:11:08
PHP 7.4.23

###Установка и запуск игры
Из Github:

Склонировать проект на локальную машину;
Установить composer, если он не установлен;
Перейти в корневой каталог;
Выполнить в консоли команду composer update;
Перейти в каталог bin из корнегого каталога и запустить файл cold-hot.bat.
Из Packagist:

Установить composer, если он не установлен;
Перейти в каталог, в который вы будете клонировать проект;
Выполнить команду composer create-project sifon/cold-hot;
Перейти в каталог bin;
Запустить файл cold-hot.bat.

###Ссылки
https://packagist.org/packages/polyushina/hangman