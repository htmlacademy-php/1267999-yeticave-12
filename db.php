<?php
require_once('helpers.php');
$con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * @return array двумерный ассоциативный массив из базы данных с названиями и символьным кодом категорий
 */
function get_categories($con)
{
    $sql_category = "SELECT id, title, code FROM category";
    $result_category = mysqli_query($con, $sql_category);
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    return $categories;
}

/**
 * @return array двумерный ассоциативный массив из базы данных для отображения лотов на главной странице
 */
function get_ads($con): array
{
    $sql_ads = "SELECT lot.id as id, name, title as category, price_rate as price, image as url, date_completion as calculation_date FROM category
    INNER JOIN lot ON category.id = lot.id_category
    INNER JOIN (SELECT id_lot, MAX(price_rate) as price_rate FROM rate
GROUP BY id_lot
        ) r on lot.id = r.id_lot
    WHERE lot.date_creation < lot.date_completion
    ORDER BY date_creation";
    $result_ads = mysqli_query($con, $sql_ads);
    $ads = mysqli_fetch_all($result_ads, MYSQLI_ASSOC);
    return $ads;
}

/**
 * @return array двумерный ассоциативный массив из базы данных для отображения id лотов
 */
function get_lots($con): array
{
    $lot_sql = "SELECT lot.id FROM lot";
    $result_lot = mysqli_query($con, $lot_sql);
    $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
    return $lots;
}

/**
 * @param int $lot_id id лота
 * @return array  ассоциативный массив из базы данных для отображения карточки лота
 */

function get_ads_lot(int $lot_id, $con)
{
    $sql = "SELECT lot.id as lot_id, title as category, name, image as url, description, date_completion as calculation_date, price_starting, step_rate, price_rate FROM lot
        INNER JOIN category ON lot.id_category = category.id
        LEFT JOIN (SELECT id_lot, MAX(price_rate) as price_rate FROM rate
GROUP BY id_lot
        ) r on lot.id = r.id_lot
        WHERE lot.id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $ads_lot = mysqli_fetch_assoc($res);
    return $ads_lot;
}

/**
 * @return array ассоциативный массив из базы данных для регистрации пользователя
 */
function get_users($con): array
{
    $sql_users = "SELECT id, date_registration, email, name, password, contacts FROM user";
    $result_users = mysqli_query($con, $sql_users);
    $users = mysqli_fetch_all($result_users, MYSQLI_ASSOC);
    return $users;
}

/**
 * @param string $email email пользователя, проходящего авторизацию
 * @param mixed $con подключение к базе данных
 * @return string[]|null имя пользователя по email из БД
 */
function get_user_information($email, $con)
{
    $sql = "SELECT id, name, password FROM user
            WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user_name = mysqli_fetch_assoc($res);
    return $user_name;
}

/**
 * @param string $search поиск по названию или описанию лота
 * @param mixed $con подключение к базе данных
 * @param int $limit количество объявлений на странице
 * @param int $page количество страниц объявлений
 * @return array ассоциативный массив found_lots - найденные лоты, count_page - количество страниц, занимаемое лотами
 */
function search_lot($search, $con, $limit, $page)
{
    $sql = "SELECT lot.id, title as category, date_creation, name, description, image as url, price_starting, date_completion FROM lot
    INNER JOIN category ON lot.id_category = category.id
    WHERE MATCH(name, description) AGAINST(?)
    ORDER BY date_creation";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $search);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $found_lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $count_page = floor(count($found_lots) / $limit);
    if ($count_page > 0) {
        if (!$page) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $limit;
        }
        $sql = "SELECT lot.id, title as category, date_creation, name, description, image as url, price_starting, date_completion FROM lot
    INNER JOIN category ON lot.id_category = category.id
    WHERE MATCH(name, description) AGAINST(?)
    ORDER BY date_creation
    LIMIT $offset, $limit";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 's', $search);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $found_lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $found = [
            "found_lots" => $found_lots,
            "count_page" => $count_page
        ];
        return $found;
    }
    $found = [
        "found_lots" => $found_lots,
        "count_page" => $count_page
    ];
    return $found;
}

/**
 * @param mixed $con подключение к базе данных
 * @param int $lot_id id лота из БД
 * @return array ставки пользователей из БД по id лота
 */
function get_users_lots($con, int $lot_id): array
{
    $sql_users_lots = "SELECT name, price_rate, DATE_FORMAT(date_rate, '%d.%m.%y %H:%i') as date_rate FROM rate
    INNER JOIN user on rate.id_user_game = user.id
    WHERE id_lot = $lot_id
    ORDER BY date_rate DESC";
    $result_lots = mysqli_query($con, $sql_users_lots);
    $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
    return $lots;
}

/**
 * @param mixed $con подключение к базе данных
 * @param int $user_id id пользователя
 * @return array ставки пользователя из БД
 */
function get_my_bets($con, int $user_id)
{
    $sql_my_bets = "SELECT lot.id as lot_id, image, title, lot.name as name, contacts, date_completion, id_user_game, price_rate, DATE_FORMAT(date_rate, '%d.%m.%y в %H:%i') as date_rate
    FROM lot
    INNER JOIN category on lot.id_category = category.id
    INNER JOIN (SELECT id_user_game, id_lot, MAX(price_rate) as price_rate, MAX(date_rate) as date_rate, contacts
    FROM rate
    RIGHT JOIN user on rate.id_user_game = user.id
    WHERE rate.id_user_game = $user_id
    GROUP BY id_lot
    ) r on lot.id = r.id_lot
    ORDER BY date_rate DESC";
    $result_my_bets = mysqli_query($con, $sql_my_bets);
    $my_bets = mysqli_fetch_all($result_my_bets, MYSQLI_ASSOC);
    return $my_bets;
}

/**
 * @param mixed $con подключение к базе данных
 * @param int $id_lot id лота из БД
 * @return array максимальная ставка лота из БД
 */
function get_max_bet($con, int $id_lot)
{
    $sql_max_bet = "SELECT MAX(price_rate) as price_rate
    FROM rate
    WHERE id_lot = $id_lot";
    $result_max_bet = mysqli_query($con, $sql_max_bet);
    $my_max_bet = mysqli_fetch_all($result_max_bet, MYSQLI_ASSOC);
    return $my_max_bet;
}

/**
 * @param mixed $con подключение к базе данных
 * @param int $category id категории
 * @param $limit int $limit количество объявлений на странице
 * @param int $page количество страниц объявлений
 * @return array ассоциативный массив found_lots - найденные лоты, count_page - количество страниц, занимаемое лотами
 */
function search_lot_by_category($con, $category, $limit, $page)
{
    $sql = "SELECT lot.id, title as category, date_creation, name, description, image as url, price_starting, date_completion FROM lot
    INNER JOIN category ON lot.id_category = category.id
    WHERE category.id = $category
    ORDER BY date_creation";
    $res = mysqli_query($con, $sql);
    $found_lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $count_page = floor(count($found_lots) / $limit);
    if ($count_page > 0) {
        if (!$page) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $limit;
        }
        $sql = "SELECT lot.id, title as category, date_creation, name, description, image as url, price_starting, date_completion FROM lot
        INNER JOIN category ON lot.id_category = category.id
        WHERE category.id = $category
        ORDER BY date_creation
        LIMIT $offset, $limit";
        $res = mysqli_query($con, $sql);
        $found_lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $found = [
            "found_lots" => $found_lots,
            "count_page" => $count_page
        ];
        return $found;
    }
    $found = [
        "found_lots" => $found_lots,
        "count_page" => $count_page
    ];
    return $found;
}

/**
 * @param mixed $con подключение к базе данных
 * @param int $category id категории
 * @return string[]|null название категории по id
 */
function get_category($con, $category)
{
    $sql_category = "SELECT title
    FROM category
    WHERE id = $category";
    $result_category = mysqli_query($con, $sql_category);
    $category = mysqli_fetch_assoc($result_category);
    return $category;
}

/**
 * @param mixed $con подключение к базе данных
 * @return array
 */
function getwinner_lots($con)
{
    $sql_lots = "SELECT id_user_game, user.name, winner, email, contacts, id_lot, date_rate, price_rate, id_category, title, code, id_user_create, lot.name, description, image, price_starting, date_completion FROM rate
    INNER JOIN lot ON rate.id_lot = lot.id
    INNER JOIN category ON lot.id_category = category.id
    INNER JOIN user ON rate.id_user_game = user.id
    WHERE price_rate IN (SELECT MAX(price_rate) FROM rate
    GROUP BY id_lot) && DATE_FORMAT(date_completion, '%Y-%m-%d') <= CURDATE() && winner IS NULL
ORDER BY id_lot";
    $result_winner_lots = mysqli_query($con, $sql_lots);
    $winner_lots = mysqli_fetch_all($result_winner_lots, MYSQLI_ASSOC);
    return $winner_lots;
}

function update_winner_lots ($con, $id_lot) {
    $sql_update = "UPDATE lot SET winner = 1
WHERE winner IS NULL && id = ?";
    $stmt = mysqli_prepare($con, $sql_update);
    mysqli_stmt_bind_param($stmt, 'i', $id_lot);
    mysqli_stmt_execute($stmt);
}

/**
 * добавляет созданный лот в бд
 * @param mixed $con подключение к базе данных
 * @param int $lot_category_id id категории лота
 * @param int $id_user_lot id пользователя в сессии
 * @param string $date_creation дата создания лота
 * @param string $lot_name имя созданного лота
 * @param string $lot_message описание созданного лота
 * @param string $lot_url адрес изображения лота
 * @param int $lot_rate цена созданного лота
 * @param string $lot_date дата созданного лота
 * @param int $lot_step шаг ставки созданного лота
 * @return int|string id последнего добавленного в бд лота
 */
function add_lot_to_db(
    $con,
    $lot_category_id,
    $id_user_lot,
    $date_creation,
    $lot_name,
    $lot_message,
    $lot_url,
    $lot_rate,
    $lot_date,
    $lot_step
) {
    $lots_bd = "INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $lots_bd, $data = [
        $lot_category_id,
        $id_user_lot,
        $date_creation,
        $lot_name,
        $lot_message,
        $lot_url,
        $lot_rate,
        $lot_date,
        $lot_step
    ]);
    mysqli_stmt_execute($stmt);
    $last_lot = mysqli_insert_id($con);
    return $last_lot;
}

/**
 * добавляет в базу данных зарегистрированного пользователя
 * @param mixed $con подключение к базе данных
 * @param string $date_registration дата регистрации пользователя
 * @param string $email email зарегистрированного пользователя
 * @param string $name имя зарегистрированного пользователя
 * @param string $hash хэш пароля зарегистрированного пользователя
 * @param string $message контактные данные
 */
function add_user_to_db($con, $date_registration, $email, $name, $hash, $message)
{
    $users_db = "INSERT INTO user (date_registration, email, name, password, contacts) VALUES (?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $users_db, $data = [$date_registration, $email, $name, $hash, $message]);
    mysqli_stmt_execute($stmt);
}

/**
 * @param mixed $con подключение к базе данных
 * @param int $user_id id пользователя который сделал ставку
 * @param int $lot_id id лота для ставки
 * @param string $today сегодняшняя дата
 * @param int $cost цена введенная пользователем
 */
function add_user_rate_to_db($con, $user_id, $lot_id, $today, $cost)
{
    $add_rate = "INSERT INTO rate (id_user_game, id_lot, date_rate, price_rate) VALUES (?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $add_rate, $data = [$user_id, $lot_id, $today, $cost]);
    mysqli_stmt_execute($stmt);
}
