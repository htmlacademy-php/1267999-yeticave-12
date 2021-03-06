<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
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
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
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
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * @param string $date_ad дата окончания аукциона объявления
 * @return array ассоциативный массив ключ is_finishing с булевым значением для проверки количества времени до снятия лота, ключ times со значением количества целых часов и минут до даты окончания аукциона
 */
function get_date(string $date_ad): array
{
    $date_today = date("Y-m-d H:i");
    $interval = strtotime($date_ad) - strtotime($date_today);
    $hours = floor($interval / 3600);
    $time_in_hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = floor(($interval - ($time_in_hours * 3600)) / 60);
    $time_in_minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    if ($time_in_hours < 1) {
        $timer_finishing = 1;
    }
    else {
        $timer_finishing = 0;
    }
    $period = [
        "is_finishing" => "$timer_finishing",
        "times" => "$time_in_hours:$time_in_minutes",
    ];
    return $period;
}

/**
 * добавляет к цене ' ₽', в случае стоимости от 1000 устанавливает разделитель тысяч
 * @param string $price цена товара, введенная пользователем
 * @return string цена товара для объявления
 */
function get_price(string $price): string
{
    if ($price >= 1000) {
        return number_format($price, 0, '', ' ') . ' ₽';
    }
    return $price . ' ₽';
}

/**
 * @param string $value значение глобального массива POST по ключу
 * @return string если true - возвращает значение глобального массива Post по ключу. если false - пустую строку
 */
function get_post_val($value)
{
    return $value ?? "";
}

/**
 * @param array $categories массив категорий из БД
 * @param string $lot_category категория лота из глобального массива POST
 * @return false|int|string id категории лота
 */
function category_id_post($categories, $lot_category) {
    $categories_title = array_column($categories, 'title');
    $categories_id = array_column($categories, 'id');
    $categories_sample = array_combine($categories_id, $categories_title);
    $category_id = array_search($lot_category, $categories_sample);
    return $category_id;
}

/**
 * @param array $categories массив категорий из БД
 * @param string $lot_category категория лота из глобального массива POST
 * @return string валидация категории лота глобального массива POST если введено значение возвращает пустую строку, если нет ошибку валидации
 */
function validate_category($categories, $lot_category) {
    $id_categories = in_array("$lot_category", array_column($categories, 'title'));
    if (empty($id_categories)) {
        return "Введите название категории";
    } else {
        return "";
    }
}

/**
 * @param mixed $lot_file значение глобального массива FILE лота
 * @return string валидация загруженного файла лота если файл удовлетворяет условиям возвращает пустую строку, если нет ошибку валидации
 */
function validate_file($lot_file)
{
    if (isset($lot_file)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $lot_file['tmp_name'];
        $file_size = $lot_file['size'];
        if (empty($file_name)) {
            return "Загрузите изображение в формате jpg, jpeg, png";
        }
        else {
            $file_type = finfo_file($finfo, $file_name);
            if (($file_type !== 'image/jpeg') and ($file_type !== 'image/png')) {
                return "Загрузите изображение в формате jpg, jpeg, png";
            }
            if ($file_size > 2000000) {
                return "Максимальный размер файла: 2 МБ";
            }
        }
    }
}

/**
 * @param string $value значение глобального массива POST по ключу
 * @param int $min минимальное значение количества символов в строке для валидации
 * @param int $max максимальное значение количества символов в строке для валидации
 * @return string валидация длины строки по параметрам
 */
function validate_correct_length($value, $min, $max) {
    $len = strlen($value);
    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
}

/**
 * @param string $value значение глобального массива POST по ключу
 * @return string валидация строки по параметрам (целое число больше 0)
 */
function validate_price($value) {
    if (ctype_digit($value)) {
        $isPriceValid = "";
    }
    else {
        $isPriceValid = "Содержимое поля должно быть целым числом больше ноля";
    }
    return $isPriceValid;
}

/**
 * @param mixed $lot_date значение глобального массива POST (время окончания объявления)
 * @param bool $date_valid_separator проверка даты на регламент
 * @return string валидация даты (больше текущей даты)
 */
function validate_date($lot_date, $date_valid_separator) {
    if ($date_valid_separator) {
        $date_today = date("Y-m-d");
        $interval = strtotime($lot_date) - strtotime($date_today);
        if ($interval > 0) {
            $date_valid = "";
        } else {
            $date_valid = "Указанная дата должна быть больше текущей даты, хотя бы на один день";
        }
    }
    else {
        $date_valid = "Указанная дата должна быть больше текущей даты, хотя бы на один день";
    }
    return $date_valid;
}

/**
 * @param mixed $error_file ошобка валидации файла
 * @param mixed $error_category ошобка валидации категории
 * @param mixed $error_date ошобка валидации даты
 * @param mixed $errors остальные ошибки валидации
 * @return mixed все ошибки валидации формы
 */
function get_errors($error_file, $error_category, $error_date, $errors)
{
    $errors['file'] = $error_file;
    $errors['category'] = $error_category;
    $errors['lot-date'] = $error_date;
    return $errors;
}









/**
 * валидация даты (указанная дата должна быть больше текущей даты, хотя бы на один день)
 */
function validateDate()
{
    $date = $_POST['lot-date'];
    $array = explode('-', $date);
    $year = $array[0];
    $month = $array[1];
    $day = $array[2];
    if (empty($array[0])) {
        $isDateValid = "Введите дату";
    }
    else {
        $isDateValid = checkdate($month, $day, $year);
        if ($isDateValid) {
            $date_today = date("Y-m-d");
            $interval = strtotime($date) - strtotime($date_today);
            if ($interval > 0) {
                $isDateValid = "";
            }
            else {
                $isDateValid = "Указанная дата должна быть больше текущей даты, хотя бы на один день";
            }
        }
        else {
            $isDateValid = "Указанная дата должна быть больше текущей даты, хотя бы на один день";
        }
    }
    return $isDateValid;
}

/**
 * валидация длины заполненного поля
 */
function isCorrectLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
}

/**
 * валидация изображения лота (изображение в формате jpg, jpeg, png, максимальный размер файла: 2 МБ)
 */
function validateFile()
{
    if (isset($_FILES['file'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        if (empty($file_name)) {
            return "Загрузите изображение в формате jpg, jpeg, png";
        }
        else {
            $file_type = finfo_file($finfo, $file_name);
            if (($file_type !== 'image/jpeg') and ($file_type !== 'image/png')) {
                return "Загрузите изображение в формате jpg, jpeg, png";
            }
            if ($file_size > 2000000) {
                return "Максимальный размер файла: 2 МБ";
            }
        }
    }
}

/**
 * валидация поля по выбору категории
 */
function validateCategory($id_category_lot)
{
    if (isset($_POST['category'])) {
        if (empty($id_category_lot)) {
            return "Введите название категории";
        } else {
            return "";
        }
    }
}

/**
 * возвращает название категории при отправке формы, иначе пустую строку
 */
function get_post_category($lot_category)
{
    return $lot_category ?? "Выберите категорию";
}

/**
 * массив с общими ошибками валидации по добавлению объявления
 */
//function get_errors ($error_file, $error_category, $errors_rules)
//{
//    $errors_rules['file'] = $error_file;
//    $errors_rules['category'] = $error_category;
//    return $errors_rules;
//}

/**
 * сохраняет отправленный файл, возвращает url adress
 */
function save_file($errors, $lot_file)
{
    if (empty($errors) || isset($lot_file)) {
        $file_name = $lot_file['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = 'uploads/' . $file_name;
        move_uploaded_file($lot_file['tmp_name'], $file_path . $file_name);
        return $file_url;
        }
}


