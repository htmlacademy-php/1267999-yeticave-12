USE yeticave;

INSERT INTO category (title, cod) VALUES ('Доски и лыжи', 'boards');
INSERT INTO category (title, cod) VALUES ('Крепления', 'attachment');
INSERT INTO category (title, cod) VALUES ('Ботинки', 'boots');
INSERT INTO category (title, cod) VALUES ('Одежда', 'clothing');
INSERT INTO category (title, cod) VALUES ('Инструменты', 'tools');
INSERT INTO category (title, cod) VALUES ('Разное', 'other');


INSERT INTO user (date_registration, email, name, password, contacts) VALUES ('2021-01-09 21:35:22', 'js@mail.ru', 'Anton', '111222', 'номер телефона 89275551144');
INSERT INTO user (date_registration, email, name, password, contacts) VALUES ('2021-01-09 23:10:50', 'css@mail.ru', 'Yaroslav', '555999', 'номер телефона 89958796532');
INSERT INTO user (date_registration, email, name, password, contacts) VALUES ('2021-01-09 22:40:50', 'php@mail.ru', 'Kostya', '222111', 'номер телефона 89621115588');

INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (1, 1, '2021-01-10 08:32:45', '2014 Rossignol District Snowboard', 'описание номер 1', 'img/lot-1.jpg', 10999, '2021-01-28 08:32:45', 100);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (1, 1, '2021-01-10 10:12:45', 'DC Ply Mens 2016/2017 Snowboard', 'описание номер 2', 'img/lot-2.jpg', 15999, '2021-01-28 10:12:45', 150);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (3, 2, '2021-01-10 10:55:57', 'Крепления Union Contact Pro 2015 года размер L/XL', 'описание номер 3', 'img/lot-3.jpg', 8000, '2021-01-10 10:55:57', 50);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (4, 2, '2021-01-10 11:15:55', 'Ботинки для сноуборда DC Mutiny Charocal', 'описание номер 4', 'img/lot-4.jpg', 10999, '2021-01-28 11:15:55', 150);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (4, 3, '2021-01-10 11:25:30', 'Куртка для сноуборда DC Mutiny Charocal', 'описание номер 5', 'img/lot-5.jpg', 7500, '2021-01-28 11:25:30', 200);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (6, 3, '2021-01-10 11:35:50', 'Маска Oakley Canopy', 'описание номер 6', 'img/lot-6.jpg', 5400, '2021-01-28 11:35:50', 250);

INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (1, 1, '2021-01-27 09:32:45', 11099);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (2, 2, '2021-01-27 09:45:57', 16299);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (3, 3, '2021-01-27 09:57:45', 8200);
/*
получить все категории
*/
SELECT title as category FROM category;

/*
получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории
*/
SELECT name, price_starting, image, price_rate, title FROM lot
    INNER JOIN rate ON lot.id = rate.id_lot
    INNER JOIN category ON lot.id_category = category.id
    WHERE lot.date_creation < lot.date_completion;

/*
показать лот по его id. Получите также название категории, к которой принадлежит лот
*/
SELECT name as lot, title as category FROM lot
    INNER JOIN category ON lot.id_category = category.id
    WHERE lot.id = 2;

/*
обновить название лота по его идентификатору
*/
UPDATE `lot` SET `name` = 'Snowboard' WHERE (`id` = '2');

/*
получить список ставок для лота по его идентификатору с сортировкой по дате
*/
SELECT lot.id as lot_id, name, rate.id as rate_id, date_rate, price_rate  FROM lot
    INNER JOIN rate ON lot.id = rate.id_lot
    WHERE lot.id = 2
    ORDER BY date_creation ASC;
