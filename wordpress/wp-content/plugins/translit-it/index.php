<?php
/*
  Plugin Name: translit it!
  Plugin URI: http://wordpress.org/extend/plugins/translit-it/
  Description: Осторожно, это БЕТА версия! Переводим или транслитерализируем адреса страниц. Используется для перевода ЧПУ с русского на английский язык.
  Version: 1.5b
  Author: Ichi-nya
  Author URI: http://profiles.wordpress.org/ichi-nya
  Plugin URI: http://ichiblog.ru/
  License: GPL2
  Copyright: 2013
 */


$talk = array(
    "№" => "#", "є" => "eh",
    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
    "е" => "e", "ё" => "e", "ж" => "j",
    "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
    "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
    "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
    "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
    "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
    "—" => "-", "«" => "", "»" => "", "…" => "", "№" => "#"
);

$iso = array(
    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
    "е" => "e", "ё" => "yo", "ж" => "zh",
    "з" => "z", "и" => "i", "й" => "j", "к" => "k", "л" => "l",
    "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
    "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "x",
    "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "",
    "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
    "—" => "-", "«" => "", "»" => "", "…" => "", "№" => "#"
);

$try_translate = false;

/*
  Обрабатываем выбор
 */

/* Стандартная функция strtolower странно работает, пришлось доделать 
 * (взять подобную из инета и модифицировал для своих нужд
 */

function str2lower($inputString) {
    return mb_strtolower($inputString, 'UTF-8');
}

function try_transtlate($title) {
    global $try_translate, $talk;
    // Была попытка перевода
    $try_translate = true;
    // Используем транслитерацию $talk
    return strtr(str2lower($title), $talk);
}

function sanitize_title_with_translit($title) {
    global $talk, $iso, $rtl_standard, $rtl_translate;
    $rtl_standard = get_option('rtl_standard');
    $rtl_translate = get_option('rtl_translate');
    switch ($rtl_standard) {
        case 'talk': return strtr(str2lower($title), $talk);
            break;
        case 'iso': return strtr(str2lower($title), $iso);
            break;
        case 'yandex': return yandex_sanitize_title($title);
            break;
        default: return $title;
            break;
    }
}

function yandex_sanitize_title($title) {
    global $talk, $try_translate, $rtl_translate;
    $status = 200;
    // Для Яндекс API 1.0 УСТАРЕЛО
    //$url = 'http://translate.yandex.net/api/v1/tr.json/translate?lang=ru-en&text=' . urlencode($title);
    // Для Яндекс API 1.5
    $ya_api_15 = get_option('ya_api_key');
    // Проверяем, введен ли API ключ от Яндекса
    if ($ya_api_15 == '') {
        return try_transtlate($title);
    }
    $url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=' .
            $ya_api_15 . '&lang=ru-en&text=' .
            urlencode($title);
    $translate = @file_get_contents($url);
    // Если не получается зайти по адресу переводчика
    $status = substr($http_response_header[0], 9, 3);
    $rtl_translate = get_option('rtl_translate');
    // Если получить данные от переводчика Яндекса не получилось.
    if ($status != 200 && $rtl_translate) {
        return try_transtlate($title);
    } elseif ($status != 200) {
        return $title;
    }
    $json = json_decode($translate, true);
    // Проверяем на ошибки получения ответа
    if ($json['code'] == !200) {
        
    } else {
        // Выбираем результат перевода
        $result = $json['text']['0'];
    };
    if ($rtl_translate) {
        $result = strtr(str2lower($result), $talk);
    }
    return $result;
}

function rtl_options_page() {
    ?>
    <div class="wrap">
        <h2>Настройки Плагина</h2>
        <p>Вы можете выбрать способ, по которому будет производиться транслитерация заголовков.</p>
        <?php
        if ($_POST['rtl_standard']) {
            // set the post formatting options
            update_option('rtl_standard', $_POST['rtl_standard']);
            update_option('rtl_translate', $_POST['rtl_translate']);
            update_option('ya_api_key', $_POST['ya_api_key']);
            echo '<div class="updated"><p>Настройки обновлены.</p></div>';
        }
        ?>

        <form method="post">
            <fieldset class="options">
                <p>Транслитерация происходит только в новых постах</p>
                <legend>Производить транслитерацию способом:</legend>
                <?php
                // Загружаем настройки из базы
                $rtl_standard = get_option('rtl_standard');
                $rtl_translate = get_option('rtl_translate');
                $ya_api_15 = get_option('ya_api_key');
                ?>
                <!-- Choice of the method of transliteration -->
                <select name="rtl_standard">
                    <option value="yandex"<?php
                    if ($rtl_standard == 'yandex') {
                        echo(' selected="selected"');
                    }
                    ?>>Yandex Translate</option>
                    <option value="talk"<?php
                    if ($rtl_standard == 'talk') {
                        echo(' selected="selected"');
                    }
                    ?>>Разговорный</option>
                    <option value="iso"<?php
                    if ($rtl_standard == 'iso') {
                        echo(' selected="selected"');
                    }
                    ?>>ISO 9-95</option>
                    <option value="off"<?php
                    if ($rtl_standard == 'off' OR $rtl_standard == '') {
                        echo(' selected="selected"');
                    }
                    ?>>Отключена</option>
                </select>
                <br />
                <p><input type="checkbox" name="rtl_translate" <?php
                    if ($rtl_translate) {
                        echo 'checked="checked"';
                    }
                    ?>value='1' />Производить транслитерацию после переводчика</p>

                <?php
                if ($rtl_standard == 'google') {
                    echo 'Гугл не работает';
                }
                ?>
                <?php
                if ($rtl_standard == 'yandex') {
                    ?> <label for="ya_api_key">Введите Ваш уникальный API-ключ:</label>
                    <br />
                    <input id="ya_api"
                           type="text"
                           name="ya_api_key"
                           size="150"
                           value="<?php echo $ya_api_15; ?>" />
                    <br />
                    Получить api-ключ Яндекса можно <a href="http://api.yandex.ru/key/form.xml?service=trnsl" target=_blank>тут</a>
                    <?php
                }
                ?>
                <br />
                <input type="submit" value="Изменить" />
            </fieldset>
        </form>
    </div>
    <?php
}

// Добавляем опции настроек
function rtl_add_menu() {
    add_options_page('Транслитерируй это!', 'Транслитерируй это!', 8, __FILE__, 'rtl_options_page');
}

add_action('admin_menu', 'rtl_add_menu');

add_action('sanitize_title', 'sanitize_title_with_translit', 0);
