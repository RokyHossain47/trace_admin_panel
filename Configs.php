<?php
require 'vendor/autoload.php';

$app_name = 'Trace';
$default_icon_color = 'text-white'; // use Bootstrap text color sintax

use Parse\ParseClient;
use Parse\ParseSessionStorage;

session_start();

try {
    ParseClient::initialize(
        'yIAEelcOnl3YnRYp9Xft6fAfI6CJLU0TLtKYf0nP',/*APP ID*/
        '2u2DEllH51wXLwDElQggSx7y7vJu3X1OgTn2ELIM', /*REST API key*/
        'AsDVQmszF2ybh9MeeYxW6tsWdfmJbCnxwUrlkkGt'/*MASTER key*/
    );
    ParseClient::setServerURL('https://parseapi.back4app.com/','/'); // For back4app use: https://parseapi.back4app.com/'
    ParseClient::setStorage( new ParseSessionStorage());
} catch (Exception $e) {
}

$health = ParseClient::getServerHealth();
if($health['status'] !== 200) {

}

// Website root url
$GLOBALS['WEBSITE_PATH'] = 'https://parseapi.back4app.com'; 