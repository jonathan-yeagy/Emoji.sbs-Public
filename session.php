<?php
require_once __DIR__ . '/includes/development.php';
require_once __DIR__ . '/includes/ext/DiscordOAuthAPI.php';

use EmojiSBS\External\Discord\OAuth\DiscordOAuthAPI;

session_start();
$oauth = new DiscordOAuthAPI();

if(!array_key_exists('action', $_GET)){
    header('Location: ' . SITE_ROOT);
    exit;
}

switch($_GET['action']){
    case 'login':
        if (!$oauth->isAuthorized()){
            // Start the login process by sending the user to Discord's authorization page
            header('Location: ' . $oauth->getLoginUrl());
            exit;
        }
        break;
    case 'authorize':
        // When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
        if(array_key_exists('code', $_GET)) {
            // Exchange the auth code for a token
            // TODO handle failed case
            $oauth->authorize($_GET['code']);
        }
        break;
    case 'logout':
        $oauth->revoke();
        session_destroy();
        header('Location: ' . SITE_ROOT);
        exit;
}
header('Location: ' . SITE_ROOT . '/landing.php');
exit;
