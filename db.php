<?php
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
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
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
    INNER JOIN rate ON lot.id = rate.id_lot
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

function get_ads_lot(int $lot_id, $con): array
{
    $sql = "SELECT lot.id as id, title as category, name, image as url, description, date_completion as calculation_date FROM lot
        INNER JOIN category ON lot.id_category = category.id
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

function search_lot($search, $con, $LIMIT_SAMPLE_LOT, $page)
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
    $count_page = floor(count($found_lots) / $LIMIT_SAMPLE_LOT);
    if ($count_page > 0) {
        $offset = ($count_page - 1) * $LIMIT_SAMPLE_LOT;
        $sql = "SELECT lot.id, title as category, date_creation, name, description, image as url, price_starting, date_completion FROM lot
    INNER JOIN category ON lot.id_category = category.id
    WHERE MATCH(name, description) AGAINST(?)
    ORDER BY date_creation
    LIMIT $offset, $LIMIT_SAMPLE_LOT";
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

$lots_bd = "INSERT INTO lot (id_category, id_user_create, date_creation, name, description, image, price_starting, date_completion, step_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$users_db = "INSERT INTO user (date_registration, email, name, password, contacts) VALUES (?, ?, ?, ?, ?)";
