# Лабораторная работа 1. Подготовка окружения для создания консольного PHP-приложения, работающего с SQLite
### Мордовский госуниверситет, ФМиИТ, 402 группа, Полюшина Д.В.

### Описание
 Компьютер загадывает слово из шести букв Ваша задача - угадать буквы, а затем и все слово целиком.
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