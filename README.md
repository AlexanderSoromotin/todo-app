# Тестовое задание To-Do List

Тестовое задание на должность Разработчик PHP. Необходимо разработать веб-приложение для управления задачами (to-do list). Пользователи
должны иметь возможность добавлять, редактировать, удалять и отмечать задачи как
выполненные.


## Требования

Для запуска проекта вам потребуется установить следующие программные средства:

- [PHP](https://www.php.net/) (рекомендуется версия 8.1 или выше)
- [Composer](https://getcomposer.org/) (для управления зависимостями)
- [MySQL](https://www.mysql.com/) (СУБД)

## Установка

1. Клонируйте репозиторий в новую директорию:

```bash
git clone https://github.com/AlexanderSoromotin/todo-app todo-app
```
2. Перейдите в директорию проекта:
```bash
cd todo-app
```
3. Установите зависимости PHP с помощью Composer:
```bash
composer install
```
4. Создайте файл .env на основе .env.example:
```bash
cp .env.example .env
```
6. Создайте новую базу данных в вашей СУБД:

6. Настройте параметры базы данных внутри файла .env:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo-app
DB_USERNAME=root
DB_PASSWORD=
```
7. Cгенерируйте ключ приложения:
```bash
php artisan key:generate
```
8. Выполните миграции базы данных:

```bash
php artisan migrate
```

## Установка
После завершения установки, вы можете запустить ваш проект следующим образом:

```bash
php artisan serve
```
Приложение будет доступно по адресу http://localhost:8000.

## Дополнительные ресурсы
Коллекция запросов в Postman: [Google Drive](https://drive.google.com/file/d/1w2c2TUfuaExxto3F1Jz9lUjYP0EQVc4q/view?usp=sharing)

Среда переменных в Postman: [Google Drive](https://drive.google.com/file/d/1sy83rL0KH-qxuAVbFKqTwtw4KnB7JCdz/view?usp=sharing)
