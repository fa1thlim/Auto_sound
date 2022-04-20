<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'my-wp' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'root' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', '' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '*7Zz9f}SbcgK9<Y|y#oQs}R5$}V$.m|Zeuc)Ll[We4uozJVP_4(?$r>w_8646+QM' );
define( 'SECURE_AUTH_KEY',  'f?XM`g{v%3Oaxg=QL*keoQ@Q x z>4a#60`^7[) AjyNr&M3dZVGA/M.~4jl6X=Z' );
define( 'LOGGED_IN_KEY',    '[0Wky7B/*Oe1X0?U$*@Lwhn7ha(pcGTSG5{Qfi%?nHwgcwotv#Mi]#+BKyltK/7M' );
define( 'NONCE_KEY',        '07<T4w>UA}51&f)20e/@PGe+LkcN*!w]E?OqI>XyQ~?z(zk2p.Q?<u{2lM}78=%j' );
define( 'AUTH_SALT',        '!%,$ s#l=gymm>w;BPqm]q0pzL|]AWSu3.R8EcQ5q}TdZ<[PRSe1=2u39c-&@Zhn' );
define( 'SECURE_AUTH_SALT', 'b2uotBvI4nx>uyCZDrVbUS0Tm&a[@p@bPsB7CMvtKPz@:&#2Y<HjNRrU=a5N39P%' );
define( 'LOGGED_IN_SALT',   'LS8l<A@JdxleW4.M^D+4x .5BqVpos: N0<?Mf*a7$+#f9,_NX}rh!S#4<2]eP{u' );
define( 'NONCE_SALT',       '>0dblCVeREun;rcVvaJu/s>,)OY3Nl*gUB(U0OVf <j32mTcj^+:ATK;Qv]zd$pv' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'zx3_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
