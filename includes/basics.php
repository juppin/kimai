<?php
/**
 * This file is part of
 * Kimai - Open Source Time Tracking // http://www.kimai.org
 * (c) 2006-2009 Kimai-Development-Team
 *
 * Kimai is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; Version 3, 29 June 2007
 *
 * Kimai is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kimai; If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Basic initialization takes place here.
 * From loading the configuration to connecting to the database this all is done
 * here.
 */

defined('WEBROOT') || define('WEBROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

if (!file_exists(WEBROOT . 'includes/autoconf.php')) {
    header('Location: installer/index.php');
    exit;
}

ini_set('display_errors', '0');

require_once WEBROOT . '/libraries/autoload.php';
require_once WEBROOT . 'includes/autoconf.php';
require_once WEBROOT . 'includes/vars.php';
require_once WEBROOT . 'includes/func.php';

$database = new Kimai_Database_Mysql($kga);
$database->connect($kga['server_hostname'], $kga['server_database'], $kga['server_username'], $kga['server_password'], $kga['utf8'], $kga['server_type']);
if (!$database->isConnected()) {
    die('Kimai could not connect to database. Check your autoconf.php.');
}
Kimai_Registry::setDatabase($database);

global $translations;
$translations = new Kimai_Translations($kga);
if ($kga['language'] != 'en') {
    $translations->load($kga['language']);
}

$vars = $database->configuration_get_data();
if (!empty($vars)) {
    $kga['currency_name'] = $vars['currency_name'];
    $kga['currency_sign'] = $vars['currency_sign'];
    $kga['show_sensible_data'] = $vars['show_sensible_data'];
    $kga['show_update_warn'] = $vars['show_update_warn'];
    $kga['check_at_startup'] = $vars['check_at_startup'];
    $kga['show_daySeperatorLines'] = $vars['show_daySeperatorLines'];
    $kga['show_gabBreaks'] = $vars['show_gabBreaks'];
    $kga['show_RecordAgain'] = $vars['show_RecordAgain'];
    $kga['show_TrackingNr'] = $vars['show_TrackingNr'];
    $kga['date_format'][0] = $vars['date_format_0'];
    $kga['date_format'][1] = $vars['date_format_1'];
    $kga['date_format'][2] = $vars['date_format_2'];
    if ($vars['language'] != '') {
        $kga['language'] = $vars['language'];
    } elseif ($kga['language'] == '') {
        $kga['language'] = 'en';
    }
}