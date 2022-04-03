<?php
// За основу можно использовать проект с практических заданий первой и второй лекций.
// 1. Найти и указать в проекте Front Controller (Page Controller) и расписать классы, которые с ним взаимодействуют.
//
// 2. Найти в проекте паттерн Registry и объяснить, почему он был применён.
//
// 3. Добавить во все классы Repository использование паттерна Identity Map вместо постоянного генерирования сущностей.


// 1. MainController.php
//    OrderController.php
//    ProductController.php
//    UserController.php
//
//    классы:
//        Basket(); - Корзина
//        Security(); - Сессия, аутентификация, разлогинивание ...
//        Product(); - Продукт
//        Request(); - Запрос
//        Response(); - Ответ
//
//
//    методы:
//        render('main/index.html.php')
//        getProductsInfo();
//        isLogged();
//        checkout();
//        getInfo();
//        getAll();
//        getUser();
//
// 2. app/framework/Registry.php
//     Добавляет контейнер для работы реестра
//     Получает данные из конфигурационного файла
//     Рендерит страницу по названию роута
//
// 3. src/Model/Repository/Product.php
//    src/Model/Repository/User.php

