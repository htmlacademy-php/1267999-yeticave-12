USE yeticave;

INSERT INTO category (title, code) VALUES ('Доски и лыжи', 'boards');
INSERT INTO category (title, code) VALUES ('Крепления', 'attachment');
INSERT INTO category (title, code) VALUES ('Ботинки', 'boots');
INSERT INTO category (title, code) VALUES ('Одежда', 'clothing');
INSERT INTO category (title, code) VALUES ('Инструменты', 'tools');
INSERT INTO category (title, code) VALUES ('Разное', 'other');


INSERT INTO user (date_registration, email, name, password, contacts) VALUES ('2021-01-09 21:35:22', 'js@mail.ru', 'Anton', '111222', 'номер телефона 89275551144');
INSERT INTO user (date_registration, email, name, password, contacts) VALUES ('2021-01-09 23:10:50', 'css@mail.ru', 'Yaroslav', '555999', 'номер телефона 89958796532');
INSERT INTO user (date_registration, email, name, password, contacts) VALUES ('2021-01-09 22:40:50', 'php@mail.ru', 'Kostya', '222111', 'номер телефона 89621115588');

INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (1, 1, '2021-03-30 08:32:45', '2014 Rossignol District Snowboard', 'описание номер 1', 'img/lot-1.jpg', 10999, '2021-03-31 08:32:45', 100);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (1, 1, '2021-03-30 10:12:45', 'DC Ply Mens 2016/2017 Snowboard', 'описание номер 2', 'img/lot-2.jpg', 15999, '2021-04-01 10:12:45', 150);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (3, 2, '2021-03-30 10:55:57', 'Крепления Union Contact Pro 2015 года размер L/XL', 'описание номер 3', 'img/lot-3.jpg', 8000, '2021-04-02 10:55:57', 50);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (4, 2, '2021-03-30 11:15:55', 'Ботинки для сноуборда DC Mutiny Charocal', 'описание номер 4', 'img/lot-4.jpg', 10999, '2021-04-03 11:15:55', 150);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (4, 3, '2021-03-30 11:25:30', 'Куртка для сноуборда DC Mutiny Charocal', 'описание номер 5', 'img/lot-5.jpg', 7500, '2021-04-04 11:25:30', 200);
INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (6, 3, '2021-03-30 11:35:50', 'Маска Oakley Canopy', 'описание номер 6', 'img/lot-6.jpg', 5400, '2021-04-05 11:35:50', 250);

INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (1, 1, '2021-02-01 09:32:45', 11099);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (2, 2, '2021-02-01 09:45:57', 16299);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (3, 3, '2021-02-01 09:57:45', 8200);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (1, 4, '2021-02-01 09:57:45', 10200);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (2, 5, '2021-02-01 09:57:45', 9300);
INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (3, 6, '2021-02-01 09:57:45', 10100);
